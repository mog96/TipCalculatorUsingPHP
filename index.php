<!DOCTYPE html>
<html>
  <head>
    <title>Tip Calculator</title>
    <link type='text/css' rel='stylesheet' href='style.css'/>
    <?php
      define('PERCENTAGES', array(10, 15, 20));
      $defaultPercentage = 15;
      define('CUSTOM_PERCENTAGE', 'CUSTOM_PERCENTAGE');
      function hasValidPercentage() {
        return isset($_POST['percentage']) and in_array((int)$_POST['percentage'], PERCENTAGES);
      }
      function hasCustomPercentage() {
        return isset($_POST['percentage']) and htmlspecialchars($_POST['percentage']) == CUSTOM_PERCENTAGE
            and isset($_POST['custom-percentage'])
            and (int)$_POST['custom-percentage'] >= 0;
      }
    ?>
    <style type="text/css">
      <?php
        $shouldShow = true;
        function showTotal($show) {
          if ($show) {
            print '.output {
                display: block;
              }';
          }
        }
        if (isset($_POST['subtotal'])) {
          if ((int)$_POST['subtotal'] <= 0) {
            $shouldShow = false;
            print 'p.subtotal {
                  color: #A52A2A;
                  font-weight: bold;
                }
                input.subtotal {
                  border: 1px solid #A52A2A;
                }';
          } else {
            showTotal($shouldShow);
          }
        }
        if (isset($_POST['percentage'])) {
          if (!hasValidPercentage() and !hasCustomPercentage()) {
            $shouldShow = false;
            print 'p.tip-percentage {
                  color: #A52A2A;
                  font-weight: bold;
                };';
          } else {
            showTotal($shouldShow);
          }
        }
      ?>
    </style>
  </head>
  <body>
  <div class="container">
    <h1 class="center">Tip Calculator</h1>
    <form method="post" action="">
      <p class="subtotal">Bill subtotal: $<input class="subtotal" type="text" name="subtotal" size="10"
          <?php
            if (isset($_POST['subtotal'])) {
              print 'value="' . $_POST['subtotal'] . '"';
            }
          ?>
        /></p>
      <p class="tip-percentage">Tip percentage:</p>
      <div>
        <ul>
          <?php
            $checkedValue = $defaultPercentage;
            if (hasValidPercentage()) {
              $checkedValue = (int)$_POST['percentage'];
            } else if (hasCustomPercentage()) {
              $checkedValue = CUSTOM_PERCENTAGE;
            }
            foreach (PERCENTAGES as $value) {
              print '<li><input type="radio" name="percentage" value="' . $value . '"';
              if ($value == $checkedValue) {
                print ' checked="checked"';
              }
              print '> ' . $value . '%' . '</li>';
            }
          ?>
        </ul>
        <p class="custom-percentage">
          <input type="radio" name="percentage" <?php
            print 'value="CUSTOM_PERCENTAGE"';
            if (hasCustomPercentage()) {
              print ' checked="checked"';
            }
          ?>>Custom: <input type="text" name="custom-percentage" size="10"<?php
            if (hasCustomPercentage()) {
              print ' value="' . (int)$_POST['custom-percentage'] . '"';
            }
          ?>>%</p>
      </div>
      <div class="center">
        <p><input type="submit" /></p>
      </div>
    </form>
    <div class="output">
      <p>Tip: $<?php
          $subtotal = (int)$_POST['subtotal'];
          $percentage = round(15 / 100, 2);
          if (hasValidPercentage()) {
            $percentage = round((int)$_POST['percentage'] / 100, 2);
          } else { // custom percentage
            $percentage = round((int)$_POST['custom-percentage'] / 100, 2);
          }
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