<?php
// Calcular el máximo número de stocks
$maxStocks = 0;
foreach ($productos as $producto) {
    $countStocks = count($producto->getColoresTallasStockFormateado($producto->idproducto));
    if ($countStocks > $maxStocks) {
        $maxStocks = $countStocks;
    }
}
?>

<table border="1" cellspacing="0" cellpadding="4">
    <thead style="background:#212529; color:white;">
        <tr>
            <th style="background:#212529; color:white;">codigo</th>
            <th style="background:#212529; color:white;">producto</th>
            <th style="background:#212529; color:white;">estado</th>
            <th style="background:#212529; color:white;">peso</th>
            <th style="background:#212529; color:white;">precio lista</th>
            <th style="background:#212529; color:white;">precio venta</th>
            <?php for ($i = 1; $i <= $maxStocks; $i++): ?>
                <th style="background:#212529; color:white;">stock<?php echo $i; ?></th>
            <?php endfor; ?>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($productos as $producto): ?>
            <tr>
                <td><?php echo htmlspecialchars($producto->codigo); ?></td>
                <td><?php echo htmlspecialchars($producto->nombre); ?></td>
                <td><?php echo htmlspecialchars($producto->estado->nombre ?? ''); ?></td>
                <td><?php echo htmlspecialchars($producto->peso); ?></td>
                <td><?php echo htmlspecialchars($producto->preciolista); ?></td>
                <td><?php echo htmlspecialchars($producto->precioventa); ?></td>

                <?php
                $stocks = $producto->getColoresTallasStockFormateado($producto->idproducto);
                foreach ($stocks as $stock):
                ?>
                    <td><?php echo htmlspecialchars($stock); ?></td>
                <?php endforeach; ?>

                <?php for ($j = count($stocks); $j < $maxStocks; $j++): ?>
                    <td></td>
                <?php endfor; ?>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
