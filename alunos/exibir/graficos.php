<?php
/**
 * graficos.php — Alunos em estágio por período (gráfico de barras).
 *
 * Substitui a antiga geração de PNG no servidor (Libchart/JpGraph e a
 * desativada Google Image Charts API) por um gráfico Chart.js no navegador,
 * com os dados vindos de uma única consulta agregada (Estagiario::contagemPorPeriodo).
 */

require_once(__DIR__ . "/../../autentica.inc");
require_once(__DIR__ . "/../../libphp/models.php");

$dados = Estagiario::contagemPorPeriodo();

$periodos    = array();
$quantidades = array();
foreach ($dados as $d) {
	$periodos[]    = $d['periodo'];
	$quantidades[] = $d['quantidade'];
}
$total = array_sum($quantidades);

// json_encode com HEX_TAG evita "</script>" no meio do bloco de dados.
$periodos_json    = json_encode($periodos, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
$quantidades_json = json_encode($quantidades, JSON_HEX_TAG);

header("Content-Type: text/html; charset=utf-8");
echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\"
	\"http://www.w3.org/TR/html4/loose.dtd\">
<html>
<head>
<meta charset=\"utf-8\">
<link href=\"../../estagio.css\" rel=\"stylesheet\" type=\"text/css\">
<title>Gráfico - Alunos por período</title>
</head>
<body>

<a href=\"javascript:history.back();\">Voltar</a><br>

<div align=\"center\">
<h3>Alunos em estágio por período — ESS/UFRJ</h3>
<div style=\"max-width: 700px;\">
<canvas id=\"grafico_alunos\" role=\"img\" aria-label=\"Gráfico de barras: alunos em estágio por período\"></canvas>
</div>
</div>

<table class=\"ficha\">
<caption>Alunos em estágio por período (total: $total)</caption>
<thead>
<tr><th>Período</th><th>Alunos</th></tr>
</thead>
<tbody>
";

foreach ($dados as $d) {
	$periodo = htmlspecialchars((string)$d['periodo'], ENT_QUOTES, 'UTF-8');
	$quantidade = (int)$d['quantidade'];
	echo "<tr><td>$periodo</td><td class=\"coluna_centralizada\">$quantidade</td></tr>\n";
}

echo "</tbody>
</table>

<script src=\"../../libjs/chartjs/chart.umd.js\"></script>
<script>
var periodos = $periodos_json;
var quantidades = $quantidades_json;

new Chart(document.getElementById('grafico_alunos'), {
	type: 'bar',
	data: {
		labels: periodos,
		datasets: [{
			label: 'Alunos',
			data: quantidades,
			backgroundColor: 'rgba(232, 145, 45, 0.75)',
			borderColor: 'rgba(160, 90, 15, 1)',
			borderWidth: 1
		}]
	},
	options: {
		responsive: true,
		plugins: {
			legend: { display: false },
			title: {
				display: true,
				text: 'Alunos em estágio por período — ESS/UFRJ'
			},
			tooltip: {
				callbacks: {
					label: function (ctx) {
						return ctx.parsed.y + ' aluno(s)';
					}
				}
			}
		},
		scales: {
			x: {
				title: { display: true, text: 'Períodos' }
			},
			y: {
				beginAtZero: true,
				ticks: { precision: 0 },
				title: { display: true, text: 'Alunos' }
			}
		}
	}
});
</script>

</body>
</html>
";
