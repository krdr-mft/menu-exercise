<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Foreign currency buying</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <script
      src="https://code.jquery.com/jquery-3.6.3.min.js"
      integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU="
      crossorigin="anonymous"></script>
  </head>
  <body>
  <div class="container">

    <div class="row text-start">
      <h1> Exchange rates for {{ $baseCurrency->name }} ({{ $baseCurrency->ISO }})</h1>

      <table class="table">
        <thead>
          <tr>
            <th scope="col">Currency</th>
            <th scope="col">ISO Code</th>
            <th scope="col">Rate</th>
            <th scope="col">Surcharge</th>
          </tr>
        </thead>
        <tbody>
        @foreach ($exchangeRates as $rate)
          <tr>
            <td>{{ $rate->name_to }}</td>
            <td>{{ $rate->to }}</td>
            <td>{{ $rate->rate }}</td>
            <td>{{ $rate->surcharge }}%</td>
          </tr>
        @endforeach
        </tbody>
      </table>
    </div>

    
      <form class="row row-cols-lg-auto g-3 align-items-center" method="POST" id="orderForm" action="/api/order">        

          <div class="col">
            <input type="text" class="form-control" id="amount" placeholder="amount">
          </div>    

          <div class="col">
            <select class="form-select" id="currency">
            @foreach ($exchangeRates as $rate)
              <option value="{{ $rate->to }}">{{ $rate->to }}</option>
              @endforeach
            </select>
          </div>
          <div class="col">
            <input type="hidden" name="baseCurrency" id="baseCurrency" value="{{$baseCurrency->ISO}}">
            <button type="submit" id="purchase" class="btn btn-primary">Purchase</button>
          </div>

      </form>
      <div class="row">
        <div class="col-4 g-3">
          <table class="table align-items-center g-3">
            <tbody>
              <tr>
                <td scope="row" class="col-8">Price:</td><td id="price" class="text-end"></td>
              </tr>
              <tr>
                <td scope="row">Surcharge amount:</td><td id="surcharge_amount" class="text-end"></td>
              </tr>
          </tbody>
          <tfoot class="table-group-divider">
              <tr>
                <td scope="row">Total:</td><td id="total" class="text-end"></td>
              </tr>
            </tbody>
          </table>
        </div>
      <div class="col"></div>
      </div>
    
  </div>
  <script> 
      var exRates = <?php echo json_encode($exchangeRates); ?>;

      $(document).ready(function(){
        $currency = $("#currency");
        $amount = $("#amount");
        $baseCurrency = $("#baseCurrency");

        $currency.on('change',function(){
          recalculate();
        })

        $amount.on('keyup',function(){
          recalculate();
        });

        $("#orderForm").submit(function(e){
          e.preventDefault();

          var form = $(this);
          var actionEndpoint = form.attr('action');

          var data = {};
              data.buy    = $currency.val();
              data.for    = $baseCurrency.val();
              data.amount = parseFloat($amount.val());

              if(isNaN(data.amount) || typeof data.amount == 'undefined')
              {
                return false;
              }

          $.ajax({
            type: "POST",
            url: actionEndpoint,
            data: data, 
            success: function(data)
              {
                alert("Currency purchased");
                console.log(data);
              }
            });

        });

      });


      function recalculate()
      {
        var currentCurrency   = $currency.val();
        var currentRate       = exRates[currentCurrency].rate;
        var currentSurcharge  = parseFloat(exRates[currentCurrency].surcharge);
        var currentAmount     = parseFloat($amount.val());

        if(typeof currentAmount === 'string' || isNaN(currentAmount) || isNaN(currentSurcharge))
        {
          setValues(0, 0, 0);
          return false;
        }

        var price = currentAmount*currentRate;
        var surchargeAmount = price*currentSurcharge/100;
        var total = price+surchargeAmount;

        setValues(price, surchargeAmount, total);
      }

      function setValues(price, surchargeAmount, totalAmount)
      {
        $("#price").html(price.toFixed(4));
        $("#surcharge_amount").html(surchargeAmount.toFixed(4));
        $("#total").html(totalAmount.toFixed(4));
      }

    </script>
  </body>
</html>