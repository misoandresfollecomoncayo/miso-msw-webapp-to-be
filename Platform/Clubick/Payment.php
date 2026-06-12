<?php require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php'; ?>

<div id="rootPayment" class="popup">
    <div style="border-bottom: 1px solid rgba(0,0,0,.1); padding-bottom: 10px; margin-bottom: 10px">
        <div><b>FACTURA:</b> {{item.invoiceNumber}}</div>
    </div>
    <div style="border-bottom: 1px solid rgba(0,0,0,.1); padding-bottom: 10px; margin-bottom: 10px">
        <div><b>PAGADO:</b> $ {{clubickApp.formatMoney(item.paid)}}</div>
    </div>
    <div style="border-bottom: 1px solid rgba(0,0,0,.1); padding-bottom: 10px; margin-bottom: 10px">
        <div><b>SALDO:</b> $ {{clubickApp.formatMoney(item.pendingPayment)}}</div>
    </div>
    <table class="table">
        <thead>
            <tr style="width: 100%">
                <th style="width: 30%">Fecha</th>
                <th style="width: 30%">Monto</th>
                <th style="width: 40%">Forma de pago</th>
            </tr>
        </thead>
        <tbody>
            <tr style="width: 100%" v-for="p in payments">
                <td style="width: 30%; text-align: center">{{p.date}}</td>
                <td style="width: 30%; text-align: center">$ {{clubickApp.formatMoney(p.amount)}}</td>
                <td style="width: 40%; text-align: center">{{p.method}}</td>
            </tr>
            <tr style="width: 100%">
                <td style="width: 30%; "><input v-model="date" type="date" style="width: 100%; border-radius: 5px; border: 1px solid rgba(0,0,0,.25)"/></td>
                <td style="width: 30%; "><input v-model="amount" type="number" style="width: 100%; border-radius: 5px; border: 1px solid rgba(0,0,0,.25)"/></td>
                <td style="width: 40%; "><input v-model="method" style="width: 100%; border-radius: 5px; border: 1px solid rgba(0,0,0,.25)"/></td>
            </tr>
        </tbody>
    </table>
    <div style="display: flex; justify-content: flex-end; margin-top: 20px">
        <button class="button-blue" v-on:click="add">Agregar</button>
    </div>
</div>
<script>
    var clubickPaymentApp = new Vue({
        el: "#rootPayment",
        data: {
            item: <?php echo json_encode(ClubickDAO::getById($_REQUEST["Id"])) ?>,
            payments: <?php echo json_encode(ClubickPaymentDAO::getAllByIdClubick($_REQUEST["Id"])) ?>,
            date: "",
            amount: 0,
            method: ""
        },
        methods: {
            add() {
                let errors = false;
        
                if (this.date === "") {
                    alert("Debe ingresar la fecha.")
                    errors = true;
                } else if (this.amount === "") {
                    alert("Debe ingresar el monto.")
                    errors = true;
                } else if (this.method === "") {
                    alert("Debe ingresar el método de pago.")
                    errors = true;
                }
                
                if (errors === false) {
                    var fd = new FormData();
                    fd.append("Date", this.date);
                    fd.append("Amount", this.amount);
                    fd.append("Method", this.method);
                    fd.append("IdClubick", this.item.id);

                    this.$http.post(URL_API + "Clubick/AddPayment.php", fd).then(function(response) {
                        this.payments.push({
                            date: this.date,
                            amount: this.amount,
                            method: this.method
                        });

                        this.date = "";
                        this.amount = "";
                        this.method = "";

                        new Notification("",response.body.body);
                    });
                }
            }
        }
    });
</script>