var Formulas = (function () {
    var iva = 0.18;

    return {
        calcularIva: function (monto) {
            return monto * iva;
        },
        calcularMontoSinIva: function (monto) {
            return monto / (1 + iva);
        },
        calcularUtilidad: function (costo, ingreso) {
            return ingreso - costo;
        },
        calcularMargenUtilidad: function (costo, ingreso) {
            return ((ingreso - costo) / costo) * 100;
        },
        calcularTasaEfectivaAnual: function (capital, interes, periodos) {
            return capital * Math.pow(1 + interes, 2);
        }
    };
})();
