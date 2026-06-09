document.addEventListener('DOMContentLoaded', function () {

    // Calcula subtotal de una fila del carrito cuando cambia la cantidad
    function actualizarSubtotal(inputCantidad) {
        var fila      = inputCantidad.closest('tr');
        var precio    = parseFloat(fila.dataset.precio);
        var cantidad  = parseInt(inputCantidad.value) || 0;
        var subtotal  = precio * cantidad;

        fila.querySelector('.celda-subtotal').textContent = '$' + subtotal.toFixed(2);
        recalcularTotal();
    }

    // Suma precio*cantidad de cada fila (no parsea el texto ya formateado,
    // que trae separador de miles y rompería parseFloat con montos >= 1000)
    function recalcularTotal() {
        var total = 0;
        document.querySelectorAll('tr[data-precio]').forEach(function (fila) {
            var precio   = parseFloat(fila.dataset.precio) || 0;
            var input    = fila.querySelector('.input-cantidad');
            var cantidad = input ? (parseInt(input.value) || 0) : 0;
            total += precio * cantidad;
        });
        var celdaTotal = document.getElementById('total-general');
        if (celdaTotal) {
            celdaTotal.textContent = '$' + total.toFixed(2);
        }
    }

    // Escucha cambios en todos los inputs de cantidad del carrito
    document.querySelectorAll('.input-cantidad').forEach(function (input) {
        input.addEventListener('input', function () {
            actualizarSubtotal(this);
        });
    });

    // Calcula subtotal en tienda al cambiar cantidad antes de agregar al carrito
    document.querySelectorAll('.input-cantidad-tienda').forEach(function (input) {
        var contenedor = input.closest('.card-footer');
        var precio     = parseFloat(input.dataset.precio);

        input.addEventListener('input', function () {
            var cantidad = parseInt(this.value) || 0;
            var subtotal = precio * cantidad;
            var spanSubtotal = contenedor.querySelector('.subtotal-tienda');
            if (spanSubtotal) {
                spanSubtotal.textContent = cantidad > 0 ? 'Subtotal: $' + subtotal.toFixed(2) : '';
            }
        });
    });
});
