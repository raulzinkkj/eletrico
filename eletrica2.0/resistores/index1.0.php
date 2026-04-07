<?php
/**
 * Sistema de Associação de Resistores (Finalizado)
 * 
 * Este arquivo integra HTML5, CSS3 (Bootstrap 5), JavaScript e PHP.
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
    <title>Calculadora de Resistores</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <style>
        body { background-color: #f4f7f6; font-family: sans-serif; }
        .card { border: none; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); margin-bottom: 20px; }
        .resistor-input-group { margin-bottom: 8px; transition: 0.2s; }
        #circuito { background: #fff; border: 1px solid #ddd; border-radius: 8px; width: 100%; height: auto; }
        .historico-item {
            background: #fff; border-left: 4px solid #0d6efd; padding: 12px; border-radius: 6px; margin-bottom: 10px;
            display: flex; justify-content: space-between; align-items: center; transition: 0.2s;
        }
        .historico-item.resolvido { border-left-color: #6c757d; opacity: 0.7; background: #f8f9fa; }
        .historico-item:hover { transform: translateY(-2px); box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
    </style>
</head>
<body>

<nav class="navbar navbar-dark bg-primary mb-4 shadow-sm">
    <div class="container">
        <span class="navbar-brand fw-bold"><i class="bi bi-cpu-fill me-2"></i> Associação de Resistores</span>
    </div>
</nav>

<div class="container">
    <?php if (isset($db_error)): ?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> <?php echo $db_error; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <!-- Lado Esquerdo: Inputs e Resultado -->
        <div class="col-md-5">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0 text-primary">Resistores em Série</h5>
                        <button class="btn btn-sm btn-primary" onclick="addResistor('serie')"><i class="bi bi-plus"></i></button>
                    </div>
                    <div id="container-serie"></div>

                    <hr class="my-4">

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0 text-success">Resistores em Paralelo</h5>
                        <button class="btn btn-sm btn-success" onclick="addResistor('paralelo')"><i class="bi bi-plus"></i></button>
                    </div>
                    <div id="container-paralelo"></div>
                </div>
                <div class="card-footer bg-white border-top-0 pb-3">
                    <button class="btn btn-dark w-100 py-2 fw-bold" onclick="salvarCalculo()">
                        <i class="bi bi-save2 me-2"></i> SALVAR CÁLCULO
                    </button>
                </div>
            </div>

            <div class="card bg-dark text-white text-center p-4">
                <small class="text-secondary text-uppercase fw-bold">Resistência Total (Req)</small>
                <h1 id="resultado-valor" class="display-4 fw-bold text-warning mb-0">0.00 Ω</h1>
                <div id="passos-detalhes" class="mt-2 small text-muted"></div>
            </div>
        </div>

        <!-- Lado Direito: Diagrama e Histórico -->
        <div class="col-md-7">
            <div class="card">
                <div class="card-header bg-white fw-bold d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-diagram-2 me-2"></i> Esquemático</span>
                    <button class="btn btn-sm btn-outline-secondary" onclick="limparTudo()">Limpar</button>
                </div>
                <div class="card-body p-0">
                    <canvas id="circuito" width="600" height="300"></canvas>
                </div>
            </div>

            <h5 class="mb-3 mt-4 text-secondary"><i class="bi bi-clock-history me-2"></i> Histórico</h5>
            <div id="historico-lista">
                <?php if (empty($historico)): ?>
                    <div class="text-center p-5 text-muted">Nenhum registro no banco.</div>
                <?php else: foreach ($historico as $row): 
                    $is_resolvido = (bool)$row['resolvido_resistores'];
                    $valores_raw = json_decode($row['valores_resistores'], true);
                    
                    // Formatação amigável dos valores
                    $resumo = "";
                    if (isset($valores_raw['serie']) && !empty($valores_raw['serie'])) 
                        $resumo .= "Série: [" . implode(",", $valores_raw['serie']) . "] ";
                    if (isset($valores_raw['paralelo']) && !empty($valores_raw['paralelo'])) 
                        $resumo .= "Paralelo: [" . implode(",", $valores_raw['paralelo']) . "]";
                ?>
                    <div class="historico-item <?php echo $is_resolvido ? 'resolvido' : ''; ?>">
                        <div>
                            <div class="fw-bold"><?php echo number_format($row['resultado_resistores'], 2); ?> Ω</div>
                            <small class="text-muted d-block"><?php echo $resumo; ?></small>
                        </div>
                        <div class="d-flex align-items-center">
                            <form method="POST" action="atualizar.php">
                                <input type="hidden" name="id" value="<?php echo $row['id_resistores']; ?>">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" onchange="this.form.submit()" <?php echo $is_resolvido ? 'checked' : ''; ?>>
                                </div>
                            </form>
                        </div>
                    </div>
                <?php endforeach; endif; ?>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function addResistor(tipo) {
        const container = document.getElementById(`container-${tipo}`);
        const div = document.createElement('div');
        div.className = 'input-group input-group-sm resistor-input-group';
        div.innerHTML = `
            <span class="input-group-text">Ω</span>
            <input type="number" class="form-control resistor-val" placeholder="Valor" oninput="calcular()" data-tipo="${tipo}">
            <button class="btn btn-outline-danger" onclick="this.parentElement.remove(); calcular();"><i class="bi bi-x"></i></button>
        `;
        container.appendChild(div);
        calcular();
    }

    function limparTudo() {
        document.getElementById('container-serie').innerHTML = '';
        document.getElementById('container-paralelo').innerHTML = '';
        calcular();
    }

    function getValores(tipo) {
        return Array.from(document.querySelectorAll(`.resistor-val[data-tipo="${tipo}"]`))
                    .map(i => parseFloat(i.value))
                    .filter(v => !isNaN(v) && v > 0);
    }

    function calcular() {
        const serie = getValores('serie');
        const paralelo = getValores('paralelo');

        const totalS = serie.reduce((a, b) => a + b, 0);
        let totalP = 0;
        if (paralelo.length > 0) {
            const somaInv = paralelo.reduce((a, b) => a + (1/b), 0);
            totalP = 1 / somaInv;
        }

        const total = totalS + totalP;
        document.getElementById('resultado-valor').innerText = total.toFixed(2) + " Ω";
        
        let txt = [];
        if(serie.length) txt.push(`Série: ${totalS.toFixed(1)}Ω`);
        if(paralelo.length) txt.push(`Paralelo: ${totalP.toFixed(1)}Ω`);
        document.getElementById('passos-detalhes').innerText = txt.join(' + ');

        desenhar(serie, paralelo);
    }

    function salvarCalculo() {
        const serie = getValores('serie');
        const paralelo = getValores('paralelo');
        const resultado = parseFloat(document.getElementById('resultado-valor').innerText);

        if (!serie.length && !paralelo.length) return alert("Adicione valores!");

        fetch("salvar.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ tipo: "misto", valores: { serie, paralelo }, resultado: resultado })
        }).then(() => location.reload());
    }

    function desenhar(serie, paralelo) {
        const canvas = document.getElementById('circuito');
        const ctx = canvas.getContext('2d');
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        
        let x = 40;
        const y = 150;
        ctx.lineWidth = 2;
        ctx.strokeStyle = "#333";

        // Desenha Linha Inicial
        ctx.beginPath(); ctx.moveTo(10, y); ctx.lineTo(x, y); ctx.stroke();

        // Série
        serie.forEach(v => {
            desenharRes(ctx, x, y, v);
            x += 60;
            ctx.beginPath(); ctx.moveTo(x, y); ctx.lineTo(x+20, y); ctx.stroke();
            x += 20;
        });

        // Paralelo
        if (paralelo.length) {
            const px = x;
            const h = (paralelo.length - 1) * 40;
            const startY = y - h/2;

            ctx.beginPath(); ctx.moveTo(px, startY); ctx.lineTo(px, startY + h); ctx.stroke();
            paralelo.forEach((v, i) => {
                const py = startY + i*40;
                ctx.beginPath(); ctx.moveTo(px, py); ctx.lineTo(px+10, py); ctx.stroke();
                desenharRes(ctx, px+10, py, v);
                ctx.beginPath(); ctx.moveTo(px+70, py); ctx.lineTo(px+80, py); ctx.stroke();
            });
            ctx.beginPath(); ctx.moveTo(px+80, startY); ctx.lineTo(px+80, startY + h); ctx.stroke();
            ctx.beginPath(); ctx.moveTo(px+80, y); ctx.lineTo(px+100, y); ctx.stroke();
            x = px + 100;
        }

        // Fim
        ctx.beginPath(); ctx.arc(x+5, y, 4, 0, 7); ctx.fill();
    }

    function desenharRes(ctx, x, y, val) {
        ctx.beginPath();
        ctx.moveTo(x, y);
        for(let i=0; i<6; i++) ctx.lineTo(x+5+i*8, y + (i%2?8:-8));
        ctx.lineTo(x+60, y);
        ctx.stroke();
        ctx.fillStyle = "#0d6efd";
        ctx.fillText(val+"Ω", x+15, y-12);
    }

    window.onload = () => { addResistor('serie'); calcular(); };
</script>
</body>
</html>
