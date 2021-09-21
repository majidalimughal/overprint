@extends('layouts.admin')

@section('styles')

@endsection
@section('content')
@php 

if(isset($billing->name))
{
  $name=explode(' ',$billing->name);
$first_name=(isset($name[0])?$name[0]:'');;

$last_name=(isset($name[1])?$name[1]:'');
}
else 
{
  $first_name=$last_name='';
}
@endphp
    <div class="row page-titles px-5">
        <div class="col-md-12 col-12 align-self-center">
            <h3><strong>Billing Details</strong></h3>
        </div>

        <div class="col-md-6">
          <div class="form-group">
            <label>First Name</label>
            <input id="first_name" type="text" value="{{$first_name}}" class="form-control" autocomplete="no-fill">
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label>Last Name</label>
            <input id="last_name" type="text" value="{{$last_name}}" class="form-control" autocomplete="no-fill">
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label>Email</label>
            <input id="email" value="{{(isset($billing->email)?$billing->email:'')}}" type="email" class="form-control" autocomplete="no-fill">
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label>Phone</label>
            <input id="phone" type="text"  value="{{(isset($billing->phone)?$billing->phone:'')}}" class="form-control" autocomplete="no-fill">
          </div>
        </div>
        <div class="col-md-12">
          <div class="form-group">
            <label>Street Address</label>
            <input id="address" type="text"  value="{{(isset($billing->address)?$billing->address:'')}}" class="form-control" autocomplete="no-fill">
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label>City</label>
            <input id="city" type="text"  value="{{(isset($billing->city)?$billing->city:'')}}" class="form-control" autocomplete="no-fill">
          </div>
        </div>

        <div class="col-md-6">
          <div class="form-group">
            <label>Postal Code</label>
            <input id="zip" type="text"  value="{{(isset($billing->zip)?$billing->zip:'')}}" class="form-control" autocomplete="no-fill">
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label>Country</label>
            <input id="country" type="text"  value="{{(isset($billing->country)?$billing->country:'')}}" class="form-control" autocomplete="no-fill">
          </div>
        </div>
        
        
    </div>
    <div class="row mt-5 px-5">
      <div class="col-md-6">
        <div id="card-element" class="my-1"></div>
            <div id="card-result" class="my-1"></div>
            
      </div>
      <div class="col-md-6">
        {{-- <div id="paypal-button-container-P-33615604GL8651634MFDAHWQ"></div> --}}
      </div>
      <div class="text-center my-5">
        <button class='btn btn-primary btn-lg' id="card-button">Save Billing Details</button>
      </div>
    </div>

@endsection


@section('js')
<script src="https://www.paypal.com/sdk/js?client-id=AXfKFsirRwxWIh2HG-26lDuoo8BL72Mr_o1ikNd6xs9sXZd04-jo3H3LX8bYwR-WGPAUHmeuFWtXRRs3&vault=true&intent=subscription" data-sdk-integration-source="button-factory"></script>
<script>
  paypal.Buttons({
      style: {
          shape: 'pill',
          color: 'gold',
          layout: 'horizontal',
          label: 'subscribe'
      },
      createSubscription: function(data, actions) {
        return actions.subscription.create({
          /* Creates the subscription */
          plan_id: 'P-33615604GL8651634MFDAHWQ',
          quantity: 1 // The quantity of the product for a subscription
        });
      },
      onApprove: function(data, actions) {
        // alert(data.subscriptionID); // You can add optional success message for the subscriber here
        console.log(data);
      }
  }).render('#paypal-button-container-P-33615604GL8651634MFDAHWQ'); // Renders the PayPal button
</script>
<script src="https://js.stripe.com/v3/"></script>
<script>
   var stripe = Stripe('{{env("STRIPE_PUBLIC_KEY")}}');

var elements = stripe.elements();
var cardElement = elements.create('card');
cardElement.mount('#card-element');


var first_name = document.getElementById('first_name');
var last_name = document.getElementById('last_name');
var cardButton = document.getElementById('card-button');
var resultContainer = document.getElementById('card-result');



cardButton.addEventListener('click', function(ev) {

  stripe.createPaymentMethod({
      type: 'card',
      card: cardElement,
      billing_details: {
        name: first_name.value+' '+last_name.value,
      },
    }
  ).then(function(result) {
    if (result.error) {
      // Display error.message in your UI
      resultContainer.textContent = result.error.message;
    } else {

        
      // You have successfully created a new PaymentMethod
      $.ajax({
          type:"POST",
          url:"{{route('save.billing.card')}}",
          data:{
              _token:$("#csrf-token")[0].content,
              payment_method:result.paymentMethod.id,
              name:first_name.value+' '+last_name.value,
              email:$('#email').val(),
              phone:$('#phone').val(),
              address:$('#address').val(),
              city:$('#city').val(),
              zip:$('#zip').val(),
              country:$('#country').val(),

          },
          success:function(response){

          },
          error:function(error){

          }
      });
    }
  });
});
</script>
@endsection




