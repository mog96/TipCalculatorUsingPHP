<!DOCTYPE html>
<html>
  <head>
    <title>Tip Calculator</title>
    <link type='text/css' rel='stylesheet' href='style.css'/>
    <style type="text/css">
      <?php
        if (isset($_POST['subtotal'])) {
          if ((int)$_POST['subtotal'] <= 0) {
            print 'p.subtotal {
                color: #A52A2A;
                font-weight: bold;
              }
              input.subtotal {
                border: 1px solid #A52A2A;
              }';
          } else {
            print '.output {
                display: block;
              }';
          }
        }
      ?>
    </style>
  </head>
  <body>
  <div class="container">
    <h1 class="center">Tip Calculator</h1>
    <form method="post" action="">
      <p class="subtotal">Bill subtotal100: $<input class="subtotal" type="text" name="subtotal" size="10"
          <?php
            if (isset($_POST['subtotal'])) {
              print 'value="' . $_POST['subtotal'] . '"';
            }
          ?>
        /></p>
      <p>Tip percentage:</p>
      <div class="percentages">
        <ul>
          <?php
            $percentages = array(10, 15, 20);
            $checkedValue = 15;
            if (isset($_POST['percentage']) and in_array((int)$_POST['percentage'], $percentages)) {
              $checkedValue = (int)$_POST['percentage'];
            }
            foreach ($percentages as $value) {
              print '<li><input type="radio" name="percentage" value="' . $value . '"';
              if ($value == $checkedValue) {
                print ' checked="checked"';
              }
              print '> ' . $value . '%' . '</li>';
            }
          ?>
        </ul>
      </div>
      <div class="center">
        <p><input type="submit" /></p>
      </div>
    </form>
    <div class="output">
      <p>Tip: $<?php
          $subtotal = (int)$_POST['subtotal'];
          $percentage = round((int)$_POST['percentage'] / 100, 2);
          $tip = round($subtotal * $percentage, 2);
          print money_format('%.2n', $tip);
        ?>
      </p>
      <p>Total: $<?php
          $total = round($subtotal + $tip, 2);
          print money_format('%.2n', $total);
        ?>
      </p>
    </div>
  </div>
  </body>
</html>