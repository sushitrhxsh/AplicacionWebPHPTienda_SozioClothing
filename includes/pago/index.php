<!doctype html>
<html lang="es">

<head>
    <title>Pago</title>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        
        <!-- Bootstrap CSS v5.2.1 -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
        <!--  Paypal Boton  -->
        <script src="https://www.paypal.com/sdk/js?client-id=AQLPxwre9oRZ-R_32WOqH0AYttjO_ohpgr6B4r2spmILv20AZ7Ro_QcJUNG8pT3RZepcennAx-1ew16c&currency=MXN"></script>
    </head>
    <body>

        

        <div class="col-md-5">
        <div id="paypal-button-container"></div>
        </div>

        <script>
            paypal.Buttons({
                style:{
                    color:'blue',
                    shape: 'pill',
                    label:'pay'
                },
                createOrder: function(data,actions){
                    return actions.order.create({
                        purchase_units:[{
                            amount: {
                                value: 100
                            } 
                        }]
                    });

                },
                onApprove: function(data, actions){
                    actions.order.capture().then(function (detalles){
                        window.location.href=" //Aqui un alerta o pagina donde diga completado el pago";
                    });
                },
                onCancel: function(data){
                    alert("Pago Cancelado");
                    console.log(data);
                }

            }).render('#paypal-button-container');
        </script>


    
        <!-- Bootstrap JavaScript Libraries -->
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js" integrity="sha384-7VPbUDkoPSGFnVtYi0QogXtr74QeVeeIs99Qfg5YCF+TidwNdjvaKZX19NZ/e6oz" crossorigin="anonymous"></script>
    </body>
