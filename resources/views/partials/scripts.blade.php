
  <!-- Javascripts -->
  <script src="/assets/js-core/slimscroll.js"></script>
  <script src="/assets/js-core/screenfull.js"></script>
  <script defer src="{{ elixir('app.js') }}"></script>
  <script src="/assets/js-core/sweetalert2.min.js"></script>
  <script src="/assets/js-core/highcharts.js"></script>
  <script src="/assets/js-core/exporting.js"></script>
  
  <script>
      $('.dateTime').datetimepicker({
          format : 'YYYY-M-D'
      })

      function submitted () {
        document.getElementById('submitButton').disabled=true;
        document.getElementById('submitButton').value='Submitting, please wait...';
        document.getElementById("ism_form").submit();
      }

      $(document).keydown(function(evt){
          //new sell (f1)
          if (evt.keyCode==112){
              evt.preventDefault();
              window.location.href = '{{route("sell.form")}}';
          }

          //sell list (Shift + Enter)
          if (evt.keyCode==13 && (evt.shiftKey)){
              evt.preventDefault();
              window.location.href = '{{route("sell.index")}}';
          }

          //new purchase (f2)
          if (evt.keyCode==113){
              evt.preventDefault();
              window.location.href = '{{route("purchase.item")}}';
          }

          //purchase list (Shift + Tab)
          if (evt.keyCode==9 && (evt.shiftKey)){
              evt.preventDefault();
              window.location.href = '{{route("purchase.index")}}';
          }

          //settings (f3)
          if (evt.keyCode==114){
              evt.preventDefault();
              window.location.href = '{{route("settings.index")}}';
          }

          //Role (f4)
          if (evt.keyCode==115){
              evt.preventDefault();
              window.location.href = '{{route("role.index")}}';
          }

          //DB Backup (f5)
          if (evt.keyCode==116){
              evt.preventDefault();
              window.location.href = '{{route("settings.backup")}}';
          }

          //Logout (f11)
          if (evt.keyCode==122){
              evt.preventDefault();
              window.location.href = '{{url("logout")}}';
          }

          //Lock (CTRL + L)
          if ((evt.ctrlKey) && evt.keyCode==76){
              evt.preventDefault();
              window.location.href = '{{url("lock")}}';
          }

          //product list (ALT+P)
          if (evt.keyCode==80 && (evt.altKey)){
              evt.preventDefault();
              window.location.href = '{{route("product.index")}}';
          }

          //new product (ALT+CTRL+P)
          if (evt.keyCode==80 && (evt.altKey) && (evt.ctrlKey)){
              evt.preventDefault();
              window.location.href = '{{route("product.new")}}';
          }

          //dashboard (CTRL+H)
          if (evt.keyCode==72 && (evt.ctrlKey)){
              evt.preventDefault();
              window.location.href = '{{route("home")}}';
          }

          //home (CTRL+U)
          if (evt.keyCode==85 && (evt.ctrlKey)){
              evt.preventDefault();
              window.location.href = '{{route("home")}}';
          }
          
          //client list (ALT+C)
          if (evt.keyCode==67 && (evt.altKey)){
              evt.preventDefault();
              window.location.href = '{{route("client.index")}}';
          }

          //new Client (ALT+CTRL+C)
          if (evt.keyCode==67 && (evt.ctrlKey) && (evt.altKey)){
              evt.preventDefault();
              window.location.href = '{{route("client.new")}}';
          }

          //user list (ALT+U)
          if (evt.keyCode==85 && (evt.altKey)){
              evt.preventDefault();
              window.location.href = '{{route("user.index")}}';
          }

          //new user (ALT+CTRL+U)
          if (evt.keyCode==85 && (evt.ctrlKey) && (evt.altKey)){
              evt.preventDefault();
              window.location.href = '{{route("user.new")}}';
          }

          //Transaction list (ALT+T)
          if (evt.keyCode==84 && (evt.altKey)){
              evt.preventDefault();
              window.location.href = '{{route("payment.list")}}';
          }

          //Expense list (ALT+E)
          if (evt.keyCode==69 && (evt.altKey)){
              evt.preventDefault();
              window.location.href = '{{route("expense.index")}}';
          }

          //Report Index (ALT+R)
          if (evt.keyCode==82 && (evt.altKey)){
              evt.preventDefault();
              window.location.href = '{{route("report.index")}}';
          }
      });
  </script>