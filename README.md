# computop-paypal-v4
Proof of concept for Computop > PayPal integration using PayPal's new checkout.js v4

This is very much a proof of concept, condensed to the bare bones.
The aim is to show that we can quite easily upgrade an existing Computop integration to use the new PayPal checkout.js version 4.

Everything runs on PHP + PHP-CURL - will try and migrate to a standalone Node.js Package.

Required changes to get things up and running:

ctpayment.php --> Supply your Computop Credentials
ctpayment.php --> Add your base URL for success and failure redirect

Difference from a regular implementation:
Upon being redirected to the https://www.computop-paygate.com/paypal.aspx page, the buyer would see a 302 redirect to PayPal.
As checkout.js expects a JSON Payload (either EC-Token or Pay-ID or BA-ID), I decided to grab the redirect url from computop through the server side.

We will then echo a simple JSON String back to checkout.js running on the client side.

When we click on the rendered PayPal button, the payment function executes and we basically just return the EC-Token we got from Computop.
This was done to increase the checkout load speed, if we include the ajax call to get the token in the payment function, the checkout load is delayed by an additional 3-5 seconds.

Once the customer approves the payment (or closes the payment flow), checkout.js will issue the onAuthorize or onCancel event. 
