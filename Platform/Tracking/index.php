<?php
    require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';

    use Cloud\Engine\PHP\HTTP\CloudEngineSession;

    if (null == CloudEngineSession::getSessionObject() ||
        !CloudEngineSession::getSessionObject()->hasPermission("Trazabilidad")) {
        header("location:" . PUBLIC_PATH_PLATFORM);
    }

    if (!CloudEngineSession::getSessionObject()->isActive() ||
        CloudEngineSession::getSessionObject()->isDeleted()) {
        header("location:" . PUBLIC_PATH_PLATFORM . "Logout.php");
    }

    $layout = new Layout();
    $layout->setTitle("Trazabilidad");
    $layout->printHead();
?>
    <body>
        <?php $layout->printMainBar(); ?>
        <div id="root" class="float-left canvas-width height-100 background-color-light-gray mobile-width-100">
            <?php $layout->printSessionBar("Trazabilidad", PUBLIC_PATH_PLATFORM . "Transversal/Dashboard.php"); ?>
            <div class="padding-3x mobile-padding-3x canvas-height overflow-auto">
                <!-- Actions -->
                <div class="width-100 margin-bottom-2x" style="display: flex; justify-content: space-between">
                    <div style="display: flex; align-items: center">
                        <div class="text-weight-bold margin-right-3x">Filtros:</div>
                        <input v-on:change="updateRecords()" v-model="startDate" type="date" class="float-left select-underline margin-right-3x" style="width: 150px" />
                        <input v-on:change="updateRecords()" v-model="endDate" type="date" class="float-left select-underline margin-right-3x" style="width: 150px" />
                        <select v-on:change="updateRecords()" v-model="country" class="float-left select-underline margin-right-3x" style="width: 200px">
                            <option value="">Todos los países</option>
                            <?php
                                $countries = CountryDAO::getCountries();
                                foreach ($countries as $c) {
                                    echo '<option value="' . $c->getIdCountry() . '">' . $c->getName() . '</option>';
                                }
                            ?>
                        </select>
                        <select v-on:change="updateRecords()" v-model="company" class="float-left select-underline margin-right-3x" style="width: 200px">
                            <option value="">Todas las empresas</option>
                            <?php
                                $companies = ShipmentCompanyDAO::getShipmentCompanies();
                                foreach ($companies as $c) {
                                    echo '<option value="' . $c->getIdShipmentCompany() . '">' . $c->getName() . '</option>';
                                }
                            ?>
                        </select>
                    </div>
                    <button v-on:click="save()" class="button-red display-inline-block text-decoration-none">GUARDAR</button>
                </div>
                <!-- Table -->
                <div style="display: block; overflow: auto" class="width-100 background-color-white border-radius box-shadow">
                    <div class="width-100 padding-3x" style="display: flex; align-items: center; justify-content: flex-end">
                        <div class="text-weight-bold margin-right-3x">Búsqueda:</div>
                        <input v-model="search" placeholder="Búsqueda" type="search" style="border:1px solid lightgray" class="border-radius padding" />
                    </div>
                    <table id="tblList" class="table stripe width-100">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Invoice</th>
                                <th>Name</th>
                                <th>Locker No.</th>
                                <th>Box No.</th>
                                <th>Entregado</th>
                                <th colspan="2">Status</th>
                                <th>Fecha</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <template v-for="(i, index) in filtredItems">
                                <template v-if="i.type == 'bill'">
                                    <tr>
                                        <td class="text-align-center">{{i.date}}</td>
                                        <td class="text-align-center">{{i.billNumber}}</td>
                                        <td>{{i.customerName}}</td>
                                        <td class="text-align-center">{{i.lockerNumber}}</td>
                                        <td></td>
                                        <td></td>
                                        <td colspan="2"></td>
                                        <td></td>
                                        <td v-on:click="openTracking(index)" style="color:blue" class="text-decoration-underline cursor-pointer">Trazabilidad</td>
                                    </tr>
                                    <tr v-for="(b, indexb) in i.boxes">
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td class="text-align-center">{{b.boxNumber}}</td>
                                        <td class="text-align-center">
                                            <input v-if='b.delivered === 0' v-on:change='deliver(index,indexb)' type="checkbox" v-model='b.delivered'/>
                                            <div v-else style="display: inline-block; width:10px; height:10px; background:#4caf50; border-radius:100%"></div>
                                        </td>
                                        <td colspan="2"><input style="width:350px" class="input-text-underline" v-model="b.lastTracking" /></td>
                                        <td><input class="input-text-underline" style="width:130px" type="date" v-model="b.trackingDate" /></td>
                                        <td></td>
                                    </tr>
                                </template>
                                <template v-if="i.type == 'shipment'">
                                    <tr>
                                        <td class="text-align-center">{{i.date}}</td>
                                        <td class="text-align-center">{{i.billNumber}}</td>
                                        <td>{{i.customerName}}</td>
                                        <td class="text-align-center">{{i.lockerNumber}}</td>
                                        <td class="text-align-center">{{i.sequenceNumber}}</td>
                                        <td class="text-align-center">
                                            <input v-if='i.delivered === 0' v-on:change='deliverShipment(index)' type="checkbox" v-model='i.delivered'/>
                                            <div v-else style="display: inline-block; width:10px; height:10px; background:#4caf50; border-radius:100%"></div>
                                        </td>
                                        <td colspan="2"><input style="width:350px" class="input-text-underline" v-model="i.lastTracking" /></td>
                                        <td><input class="input-text-underline" style="width:130px" type="date" v-model="i.trackingDate" /></td>
                                        <td v-on:click="openTracking(index)" style="color:blue" class="text-decoration-underline cursor-pointer">Trazabilidad</td>
                                    </tr>
                                </template>
                            </template>
                        </tbody>
                    </table>
                </div>
                <!-- Pages -->
                <div v-if='search === ""' class="margin-top-3x pagination">
                    <template>
                        <button v-for="(p, index) in pages" v-bind:class="p === currentPage ? 'selected' : ''" v-on:click="currentPage = index + 1">{{p}}</button>
                    </template>
                </div>
            </div>
        </div>
        <?php $layout->printJSScripts(); ?>
        <script type="text/javascript">
            var app = new Vue({
                el: "#root",
                data: {
                    search: "",
                    startDate: "",
                    endDate: "",
                    country: "",
                    company: "",
                    items: new Array(),
                    pages: 0,
                    itemsPerPage: 1000000,
                    currentPage: 1
                },
                methods: {
                    save: function() {
                        var counter = 0;
                        
                        for (var i=0; i<this.filtredItems.length; i++) {
                            if (this.filtredItems[i].type === "bill") {
                                var boxes = this.filtredItems[i].boxes;
                                for (var j=0; j<boxes.length; j++) {
                                    var box = boxes[j];
                                    if (box.initialLastTracking !== box.lastTracking) {
                                        counter ++;
                                        box.initialLastTracking = box.lastTracking;
                                        var fd = new FormData();
                                        fd.append("Description",box.lastTracking);
                                        fd.append("Date",box.trackingDate);
                                        fd.append("IdBillItem",box.id);
                                        this.$http.post(URL_API + "Tracking/Save.php", fd).then(function(response){

                                        });
                                    }
                                }
                            } else {
                                var item = this.filtredItems[i];
                                if (item.initialLastTracking !== item.lastTracking) {
                                    counter ++;
                                    item.initialLastTracking = item.lastTracking;
                                    var fd = new FormData();
                                    fd.append("IdShipping",item.id);
                                    fd.append("Date",item.trackingDate);
                                    fd.append("Text",item.lastTracking);
                                    this.$http.post(URL_API + "Shipment/Tracking.php", fd).then(function(response){

                                    });
                                }
                            }
                        }
                        
                        if (counter > 0) {
                            new Notification("SUCCESS", counter + " registros actualizados");
                        }
                    },
                    deliverShipment: function(index) {
                        if (confirm("¿Confirma cambiar el estado del registro?")) {
                            var fd = new FormData();
                            fd.append("Id", this.filtredItems[index].id);
                            this.$http.post(URL_API + "Shipment/Deliver.php", fd).then(function(response){

                            });
                        } else {
                            this.filtredItems[index].delivered = 0;
                        }
                    },
                    deliver: function(index,indexb) {
                        if (confirm("¿Confirma cambiar el estado del registro?")) {
                            var fd = new FormData();
                            fd.append("Id", this.filtredItems[index].boxes[indexb].id);
                            this.$http.post(URL_API + "Bill/Deliver.php", fd).then(function(response){

                            });
                        }
                    },
                    openTracking: function(index) {
                        if (this.items[index].type === "bill") {
                            $.redirect("/Platform/Bills/Tracking.php", {IdBill: this.filtredItems[index].id}, "POST", "_blank");
                        } else {
                            $.redirect("/Platform/Shipments/Tracking.php", {IdShipment: this.filtredItems[index].id}, "POST", "_blank");
                        }
                    },
                    updateRecords: function() {
                        if (this.startDate !== "" && this.endDate !== "") {
                            this.items = new Array();
                            showPreload();
                            var fd = new FormData();
                            fd.append("StartDate",this.startDate);
                            fd.append("EndDate",this.endDate);
                            fd.append("Country",this.country);
                            fd.append("Company",this.company);
                            this.$http.post(URL_API + "Bill/Get.php", fd).then(function(response){
                                var json = response.body;
                                for (var i=0; i<json.length; i++) {
                                    this.items.push(json[i]);
                                }
                                this.pages = Math.ceil(this.items.length / this.itemsPerPage);
                                closePreload();
                            });
                        }
                    }
                },
                computed: {
                    filtredItems() {
                        var filtred = new Array();
                        
                        for (var i=((this.currentPage - 1) * this.itemsPerPage); i<(this.currentPage * this.itemsPerPage) && this.items.length > 0; i++) {
                            var item = this.items[i];
                            if (item !== undefined) {
                                filtred.push(item);
                            }
                        }
                        
                        if (this.search !== "") {
                            var filtred = new Array();
                            
                            for (var i=0; i<this.items.length; i++) {
                                var item = this.items[i];
                                if (item.type === "bill" &&
                                        (item.billNumber.toString().toLowerCase().indexOf(this.search.toString().toLowerCase()) > -1
                                        || item.customerName.toString().toLowerCase().indexOf(this.search.toString().toLowerCase()) > -1
                                        || item.lockerNumber.toString().toLowerCase().indexOf(this.search.toString().toLowerCase()) > -1)) {
                                    filtred.push(item);
                                }
                                
                                if (item.type === "bill" &&
                                        item.boxes.length > 0) {
                                    for (var j=0; j<item.boxes.length; j++) {
                                        var box = item.boxes[j];
                                        if (box.boxNumber.toString().toLowerCase().indexOf(this.search.toString().toLowerCase()) > -1) {
                                            filtred.push(item);
                                        }
                                    }
                                }
                                
                                if (item.type === "shipment" &&
                                        (item.billNumber.indexOf(this.search) > -1
                                        || item.sequenceNumber.toString().toLowerCase().indexOf(this.search.toString().toLowerCase()) > -1
                                        || item.customerName.toString().toLowerCase().indexOf(this.search.toString().toLowerCase()) > -1
                                        || item.lockerNumber.toString().toLowerCase().indexOf(this.search.toString().toLowerCase()) > -1)) {
                                    filtred.push(item);
                                }
                            }
                        }
                    
                        return filtred;
                    }
                }
            });
        </script>
    </body>
</html>