<?php
/**
 * Sistema de Associação de Resistores (Misto e com Fonte de Energia)
 */

// 1. Conexão com o Banco de Dados
try {
    if (file_exists("conexao.php")) {
        require "conexao.php";
    } else {
        throw new Exception("Arquivo 'conexao.php' não encontrado.");
    }
} catch (Exception $e) {
    $db_error = $e->getMessage();
}

// 2. Busca de Histórico
$historico = [];
if (isset($conexao) && $conexao instanceof PDO) {
    try {
        $stmt = $conexao->query("SELECT * FROM resistores ORDER BY id_resistores DESC LIMIT 15");
        $historico = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $db_error = "Erro no histórico: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simulador de Circuito de Resistores</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <style>
        body { background-color: #f0f2f5; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .card { border: none; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); margin-bottom: 20px; }
        .block-card { border-left: 6px solid #0d6efd; margin-bottom: 15px; background: #fff; padding: 15px; border-radius: 10px; }
        .block-paralelo { border-left-color: #198754; }
        .resistor-input { margin-bottom: 5px; }
        #circuito { background: #fff; border: 2px solid #e9ecef; border-radius: 15px; width: 100%; height: auto; }
        .historico-item { background: #fff; padding: 12px; border-radius: 10px; margin-bottom: 8px; border: 1px solid #eee; transition: 0.2s; }
        .historico-item:hover { transform: translateY(-2px); box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        .result-box { background: #212529; color: #fff; border-radius: 15px; padding: 25px; text-align: center; }
        .badge-volt { background: #ffc107; color: #000; }
        .badge-amp { background: #0dcaf0; color: #000; }
    </style>
</head>
<body>

<nav class="navbar navbar-dark bg-dark mb-4">
    <div class="container">
        <span class="navbar-brand fw-bold"><i class="bi bi-battery-charging me-2 text-warning"></i> Simulador de Circuitos DC</span>
    </div>
</nav>

<div class="container">
    <div class="row">
        <!-- Coluna de Configuração -->
        <div class="col-lg-5">
            <!-- Fonte de Tensão -->
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title text-warning"><i class="bi bi-lightning-fill"></i> Fonte de Tensão (Bateria)</h5>
                    <div class="input-group">
                        <span class="input-group-text">Voltagem (V)</span>
                        <input type="number" id="voltagem" class="form-control form-control-lg" value="12" step="any" oninput="calcular()">
                        <span class="input-group-text">Volts</span>
                    </div>
                </div>
            </div>

            <!-- Blocos de Resistores -->
            <div class="card">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Componentes do Circuito</h5>
                    <div>
                        <button class="btn btn-sm btn-outline-primary" onclick="addBlock('serie')">+ Série</button>
                        <button class="btn btn-sm btn-outline-success" onclick="addBlock('paralelo')">+ Paralelo</button>
                    </div>
                </div>
                <div class="card-body" id="blocks-container" style="max-height: 500px; overflow-y: auto;">
                    <!-- Blocos dinâmicos aqui -->
                </div>
                <div class="card-footer bg-white">
                    <button class="btn btn-dark w-100 fw-bold" onclick="salvarCalculo()">
                        <i class="bi bi-cloud-upload me-2"></i> SALVAR NO HISTÓRICO
                    </button>
                </div>
            </div>
        </div>

        <!-- Coluna de Resultados e Visualização -->
        <div class="col-lg-7">
            <div class="result-box mb-4 shadow-lg">
                <div class="row">
                    <div class="col-md-4 border-end border-secondary">
                        <small class="text-muted d-block mb-1">RESISTÊNCIA TOTAL</small>
                        <h2 id="res-total" class="text-warning fw-bold mb-0">0.00 Ω</h2>
                    </div>
                    <div class="col-md-4 border-end border-secondary">
                        <small class="text-muted d-block mb-1">TENSÃO (V)</small>
                        <h2 id="volt-display" class="text-info fw-bold mb-0">12.0 V</h2>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block mb-1">CORRENTE (I)</small>
                        <h2 id="corrente-total" class="text-success fw-bold mb-0">0.00 A</h2>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-white fw-bold d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-diagram-3 me-2"></i> Esquemático do Circuito Fechado</span>
                    <button class="btn btn-sm btn-light" onclick="limparTudo()">Limpar Tudo</button>
                </div>
                <div class="card-body p-0 text-center">
                    <canvas id="circuito" width="800" height="400"></canvas>
                </div>
            </div>

            <h5 class="mt-4 mb-3 text-secondary"><i class="bi bi-clock-history me-2"></i> Histórico Recente</h5>
            <div id="historico-lista">
                <?php if (empty($historico)): ?>
                    <div class="text-center p-4 text-muted">Sem registros.</div>
                <?php else: foreach ($historico as $row): 
                    $is_resolvido = (bool)$row['resolvido_resistores'];
                ?>
                    <div class="historico-item d-flex justify-content-between align-items-center <?php echo $is_resolvido ? 'opacity-50' : ''; ?>">
                        <div>
                            <span class="badge bg-dark me-2"><?php echo number_format($row['resultado_resistores'], 2); ?> Ω</span>
                            <small class="text-muted">Cálculo #<?php echo $row['id_resistores']; ?></small>
                        </div>
                        <form method="POST" action="atualizar.php">
                            <input type="hidden" name="id" value="<?php echo $row['id_resistores']; ?>">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" onchange="this.form.submit()" <?php echo $is_resolvido ? 'checked' : ''; ?>>
                            </div>
                        </form>
                    </div>
                <?php endforeach; endif; ?>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    let blocks = []; // [{id, tipo, valores}]

    function addBlock(tipo) {
        const id = Date.now();
        const block = { id, tipo, valores: [10] }; // Valor padrão 10Ω
        blocks.push(block);
        renderBlocks();
        calcular();
    }

    function removeBlock(id) {
        blocks = blocks.filter(b => b.id !== id);
        renderBlocks();
        calcular();
    }

    function updateResistor(blockId, resIndex, value) {
        const block = blocks.find(b => b.id === blockId);
        if (block) {
            block.valores[resIndex] = parseFloat(value) || 0;
            calcular();
        }
    }

    function addResistorToBlock(blockId) {
        const block = blocks.find(b => b.id === blockId);
        if (block) {
            block.valores.push(10);
            renderBlocks();
            calcular();
        }
    }

    function removeResistorFromBlock(blockId, resIndex) {
        const block = blocks.find(b => b.id === blockId);
        if (block && block.valores.length > 1) {
            block.valores.splice(resIndex, 1);
            renderBlocks();
            calcular();
        }
    }

    function renderBlocks() {
        const container = document.getElementById('blocks-container');
        container.innerHTML = '';
        
        blocks.forEach((block, bIdx) => {
            const div = document.createElement('div');
            div.className = `block-card ${block.tipo === 'paralelo' ? 'block-paralelo' : ''}`;
            
            let resistorsHtml = block.valores.map((v, rIdx) => `
                <div class="input-group input-group-sm mb-1">
                    <span class="input-group-text">R${rIdx+1}</span>
                    <input type="number" class="form-control" value="${v}" oninput="updateResistor(${block.id}, ${rIdx}, this.value)">
                    <button class="btn btn-outline-danger" onclick="removeResistorFromBlock(${block.id}, ${rIdx})"><i class="bi bi-trash"></i></button>
                </div>
            `).join('');

            div.innerHTML = `
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <strong class="text-uppercase small">${block.tipo === 'serie' ? 'Bloco Série' : 'Bloco Paralelo'}</strong>
                    <div>
                        <button class="btn btn-xs btn-link text-success p-0 me-2" onclick="addResistorToBlock(${block.id})"><i class="bi bi-plus-circle"></i></button>
                        <button class="btn btn-xs btn-link text-danger p-0" onclick="removeBlock(${block.id})"><i class="bi bi-x-lg"></i></button>
                    </div>
                </div>
                ${resistorsHtml}
            `;
            container.appendChild(div);
        });
    }

    function calcular() {
        const V = parseFloat(document.getElementById('voltagem').value) || 0;
        let ReqTotal = 0;

        blocks.forEach(block => {
            const vals = block.valores.filter(v => v > 0);
            if (vals.length === 0) return;

            if (block.tipo === 'serie') {
                ReqTotal += vals.reduce((a, b) => a + b, 0);
            } else {
                const somaInversos = vals.reduce((a, b) => a + (1/b), 0);
                ReqTotal += (somaInversos > 0) ? (1 / somaInversos) : 0;
            }
        });

        const I = (ReqTotal > 0) ? (V / ReqTotal) : 0;

        document.getElementById('res-total').innerText = ReqTotal.toFixed(2) + " Ω";
        document.getElementById('volt-display').innerText = V.toFixed(1) + " V";
        document.getElementById('corrente-total').innerText = I.toFixed(3) + " A";

        desenharCircuito(V, ReqTotal, I);
    }

    function limparTudo() {
        blocks = [];
        renderBlocks();
        calcular();
    }

    function salvarCalculo() {
        const resTotal = parseFloat(document.getElementById('res-total').innerText);
        if (blocks.length === 0) return alert("Adicione componentes!");

        fetch("salvar.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({
                tipo: "misto_avançado",
                valores: blocks,
                resultado: resTotal
            })
        }).then(() => location.reload());
    }

    /**
     * Desenho do Circuito no Canvas
     */
    function desenharCircuito(V, Req, I) {
        const canvas = document.getElementById('circuito');
        const ctx = canvas.getContext('2d');
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        
        const margin = 60;
        const W = canvas.width - (margin * 2);
        const H = 200;
        const startX = margin;
        const startY = 100;

        ctx.strokeStyle = "#333";
        ctx.lineWidth = 2;

        // 1. Desenha a Bateria (Lado Esquerdo)
        const batX = startX;
        const batY = startY + H/2;
        
        // Linhas da bateria
        ctx.beginPath();
        ctx.moveTo(batX, startY);
        ctx.lineTo(batX, batY - 15);
        ctx.moveTo(batX, batY + 15);
        ctx.lineTo(batX, startY + H);
        ctx.stroke();

        // Placas da bateria
        ctx.beginPath();
        ctx.lineWidth = 4;
        ctx.moveTo(batX - 15, batY - 15); ctx.lineTo(batX + 15, batY - 15); // Positivo
        ctx.stroke();
        ctx.beginPath();
        ctx.lineWidth = 2;
        ctx.moveTo(batX - 8, batY + 15); ctx.lineTo(batX + 8, batY + 15); // Negativo
        ctx.stroke();

        ctx.fillStyle = "#ffc107";
        ctx.font = "bold 14px Arial";
        ctx.fillText(V + "V", batX - 45, batY + 5);
        ctx.fillText("+", batX + 10, batY - 20);

        // 2. Linha Superior (Conectando blocos)
        ctx.lineWidth = 2;
        ctx.beginPath();
        ctx.moveTo(batX, startY);
        ctx.lineTo(batX + 40, startY);
        ctx.stroke();

        let curX = batX + 40;
        const blockW = 100;
        const gap = 30;

        blocks.forEach((block, idx) => {
            if (block.tipo === 'serie') {
                // Desenha resistores em linha
                block.valores.forEach(v => {
                    desenharResistor(ctx, curX, startY, 50, v, true);
                    curX += 50;
                    ctx.beginPath(); ctx.moveTo(curX, startY); ctx.lineTo(curX + 15, startY); ctx.stroke();
                    curX += 15;
                });
            } else {
                // Desenha bloco paralelo
                const pW = 60;
                const vGap = 30;
                const totalH = (block.valores.length - 1) * vGap;
                const pStartY = startY - totalH/2;

                ctx.beginPath(); ctx.moveTo(curX, startY); ctx.lineTo(curX, pStartY); ctx.lineTo(curX, pStartY + totalH); ctx.stroke();
                
                block.valores.forEach((v, i) => {
                    const py = pStartY + i*vGap;
                    ctx.beginPath(); ctx.moveTo(curX, py); ctx.lineTo(curX + 10, py); ctx.stroke();
                    desenharResistor(ctx, curX + 10, py, 40, v, false);
                    ctx.beginPath(); ctx.moveTo(curX + 50, py); ctx.lineTo(curX + 60, py); ctx.stroke();
                });

                ctx.beginPath(); ctx.moveTo(curX + 60, pStartY); ctx.lineTo(curX + 60, pStartY + totalH); ctx.stroke();
                ctx.beginPath(); ctx.moveTo(curX + 60, startY); ctx.lineTo(curX + 75, startY); ctx.stroke();
                curX += 75;
            }
        });

        // 3. Fecha o Circuito (Linha final até o canto e volta por baixo)
        const endX = canvas.width - margin;
        ctx.beginPath();
        ctx.moveTo(curX, startY);
        ctx.lineTo(endX, startY);
        ctx.lineTo(endX, startY + H);
        ctx.lineTo(batX, startY + H);
        ctx.stroke();

        // Informações de Corrente
        if (I > 0) {
            ctx.fillStyle = "#198754";
            ctx.font = "italic 13px Arial";
            ctx.fillText("I = " + I.toFixed(3) + " A", (batX + endX)/2 - 30, startY + H + 20);
            
            // Seta de corrente
            const arrowX = (batX + endX)/2;
            ctx.beginPath();
            ctx.moveTo(arrowX + 20, startY + H);
            ctx.lineTo(arrowX - 20, startY + H);
            ctx.lineTo(arrowX - 10, startY + H - 5);
            ctx.moveTo(arrowX - 20, startY + H);
            ctx.lineTo(arrowX - 10, startY + H + 5);
            ctx.stroke();
        }
    }

    function desenharResistor(ctx, x, y, w, val, horizontal) {
        ctx.beginPath();
        ctx.moveTo(x, y);
        const zigzags = 5;
        const step = (w - 10) / zigzags;
        for(let i=0; i<zigzags; i++) {
            ctx.lineTo(x + 5 + i*step, y + (i%2 ? 6 : -6));
        }
        ctx.lineTo(x + w, y);
        ctx.stroke();
        
        ctx.fillStyle = "#0d6efd";
        ctx.font = "10px Arial";
        ctx.fillText(val + "Ω", x + w/2 - 10, y - 10);
    }

    // Inicialização
    window.onload = () => {
        addBlock('serie');
        calcular();
    };
</script>

</body>
</html>
