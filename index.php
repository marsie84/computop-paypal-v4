<!DOCTYPE html>
<html lang="en">
  <head>
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
      <title>Computop - Checkout.js demo</title>
      <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
      <script src="https://www.paypalobjects.com/api/checkout.js" data-version-4></script>

  </head>
  <body>
  <div class="container">
    <nav class="navbar bg-faded navbar-toggleable-md navbar-light">
      <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <a class="navbar-brand" href="#"><image class="or_header__svg img-fluid" src="https://demo.paypal.com/demo/img/pp_demo_h_rbg.svg" style="max-width:200px;"></image></a>

      <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
        <div class="navbar-nav">
          <a class="nav-item nav-link">Mein Warenkorb |</a>
          <a class="nav-item nav-link">Anmelden |</a>
          <a class="nav-item nav-link active">Pruefen &amp; Absenden |</a>
          <a class="nav-item nav-link">Danke!</a>
        </div>
      </div>
    </nav>

    <div class="container" style="padding-top:100px;">
      <div class="row">
        <div class="col-md-3">
          <button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#exampleModalLong">
            Zahlungsart wählen
          </button>
        </div>
        <div class="col-md-9">
            <div class="row">
              <div class="col-md-10">
                <b>1 x  Handtuch Set, my home, »Lisa«, aus reiner Baumwolle (7-tlg.)</b>
              </div>
              <div class="col-md-2">
                16.09EUR
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <hr>
                <table class="table table-bordered table-striped">
                  <tr>
                    <td>Zwischensumme</td>
                    <td>16.09</td>
                  </tr>
                  <tr>
                    <td>Gesamtsumme</td>
                    <td>16.09</td>
                  </tr>
                </table>
              </div>
            </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-8 col-md-push-4"></div>
        <div class="col-md-4 col-md-pull-8">
          <div id="paypal-button-container" style="display:none;"></div>
          <div id="credit-card-container" class="btn btn-danger btn-block" style="display:block;">Jetzt bezahlen</div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12" id="resultDiv" style="padding-top:20px;">

        </div>
      </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLongTitle">Zahlungsart</h5>
          </div>
          <div class="modal-body">

                <div class="btn btn-primary btn-block pickerModal" data-dismiss="modal" id="pppick">PayPal</div>
                <div class="btn btn-primary btn-block pickerModal" data-dismiss="modal" >Credit Card</div>

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>


      <script>

        $('.pickerModal').on('click', function (e) {
          console.log(e);
          if(e.target.id==='pppick'){
            console.log('showing PP Button');
            $('#paypal-button-container').empty().hide();
            $('#credit-card-container').hide();
            createButton();
          } else {
            console.log('showing Card Button');
            $('#paypal-button-container').empty().hide();
            $('#credit-card-container').show();
          }
        });
        // console.log(paypal);
        function createButton(ctToken){

          $.ajax({
           type:"Get",
           url:"ctpayment.php",
           async:true,
           cache:false,
           timeout: 5000,
           error: function(){
            // will fire when timeout is reached
            $('#paypal-button-container').show().html('<div class="alert alert-danger text-center">Error loading PayPal Button</div>');
           },
           success:function(data) {

             $('#paypal-button-container').show();
             var data = JSON.parse(data);
             var ctToken = data.paymentToken;
             paypal.Button.render({

                locale: 'de_DE',

                style: {
                  size: 'responsive',
                  color: 'gold',
                  label: 'pay',
                  shape: 'rect'
                },

                env: 'sandbox', // sandbox | production

                // Show the buyer a 'Pay Now' button in the checkout flow
                commit: true,

                // payment() is called when the button is clicked
                payment: function() {
                    console.log(ctToken);
                    return ctToken;
                },

                // onAuthorize() is called when the buyer approves the payment
                onAuthorize: function(data, actions) {

                     console.log(data);
                     console.log(actions);

                    // Set up a url on your server to execute the payment
                    var EXECUTE_URL = data.returnUrl;

                    // Set up the data you need to pass to your server

                    console.log('We should now access the returnUrl. As the return URL is likely the computop domain, we cant just launch an XHR Request. We could however call a proxy on the same domain, which then launches the request to computop');
                    console.log(EXECUTE_URL);

                    var printData = JSON.stringify(data);
                    $('#resultDiv').append('<div class="alert alert-success"><b>Successfully authorized payment.</b><br>Next step is to redirect to the Computop returnURL, which will execute the payment. Return Data Object: <br>' + printData + '<br><a class="btn btn-primary btn-block" href="' + data.returnUrl +  '" role="button">Execute Payment</a></div>');

                    //window.location.href = EXECUTE_URL;

                    // Make a call to your server to execute the payment

                },
                onCancel: function(data, actions) {
                  console.log(data);
                  console.log(actions);
                  var printData = JSON.stringify(data);
                  $('#resultDiv').append('<div class="alert alert-warning"><b>Received cancel notification.</b><br>The user has likely the paypal window or clicked the cancel link. Next step: redirect to Computop cancelURL. Cancel Data Object: <br>' + printData + '<br></div>');

                  var CANCEL_URL = data.cancelUrl;

                  //window.location.href = CANCEL_URL;

                },
                onError: function(err) {

                  var printData = JSON.stringify(err);
                  $('#resultDiv').append('<div class="alert alert-danger"><b>An error occured.</b><br>Error Data Object:<br>' + printData + '<br></div>');

                }

            }, '#paypal-button-container');
          }
        });
        }

    </script>
  </body>
</html>
