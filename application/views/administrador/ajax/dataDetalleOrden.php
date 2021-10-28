<table class="table table-hover " >
    <thead>
        <tr>
            <th> Detalle </th>
            <th> Valor </th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>SUBTOTAL INGRESOS PROPIOS</td>
            <td>$ <?= number_format($SubTotalIngresosPropios) ?></td>
        </tr> 
        <tr>
            <td>IVA</td>
            <td>$ <?= number_format($Iva) ?></td>
        </tr> 
        <tr>
            <td>RETE FUENTE</td>
            <td>$ <?= number_format($rteFuente) ?></td>
        </tr> 
        <tr>
            <td>RETE ICA</td>
            <td>$ <?= number_format($rteIca) ?></td>
        </tr> 
        <tr>
            <td>RETE IVA</td>
            <td>$ <?= number_format($rteIva) ?></td>
        </tr> 
        <tr>
            <td>TOTAL INGRESOS PROPIOS</td>
            <td>$ <?= number_format($IngresosPropios) ?></td>
        </tr> 
        <tr>
            <td>TOTAL INGRESOS TERCEROS</td>
            <td>$ <?= number_format($IngresosTerceros) ?></td>
        </tr> 
        <tr>
            <td><b>TOTAL</b></td>
            <td>$ <?= number_format($total) ?></td>
        </tr> 
    </tbody>
</table>