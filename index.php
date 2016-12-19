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
      function hasValidCustomPercentage() {
        return isset($_POST['percentage']) and htmlspecialchars($_POST['percentage']) == CUSTOM_PERCENTAGE
            and !empty($_POST['custom-percentage'])
            and (int)$_POST['custom-percentage'] > 0;
      }
    ?>
    <style type="text/css">
      <?php
        if (isset($_POST['subtotal']) and isset($_POST['percentage']) and isset($_POST['split'])) {
          $shouldShow = true;
          function showTotal($show) {
            if ($show) {
              print '.output {
                  display: block;
                }';
            }
          }
          if ((int)$_POST['subtotal'] <= 0) {
            $shouldShow = false;
            print 'p.subtotal {
                  color: #A52A2A;
                  font-weight: bold;
                }
                input.subtotal {
                  border: 1px solid #A52A2A;
                }';
          }
          if (!(hasValidPercentage() or hasValidCustomPercentage())) {
            $shouldShow = false;
            print 'p.tip-percentage {
                  color: #A52A2A;
                  font-weight: bold;
                };';
          }
          if ((int)$_POST['split'] <= 0) {
            $shouldShow = false;
            print 'p.split {
                  color: #A52A2A;
                  font-weight: bold;
                }
                input.split {
                  border: 1px solid #A52A2A;
                }';
          }
          showTotal($shouldShow);
        }

        // Whether to show tip/total for each in split.
        if (empty($_POST['split']) or (int)$_POST['split'] <= 1) {
          print '.split-output {
                  display: none;
                }';
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
            if (!empty($_POST['percentage'])) {
              $checkedValue = (int)$_POST['percentage'];
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
            if (!empty($_POST['percentage']) and htmlspecialchars($_POST['percentage']) == CUSTOM_PERCENTAGE) {
              print ' checked="checked"';
            }
          ?>
          />Custom: <input type="text" name="custom-percentage" size="10"<?php
            if (!empty($_POST['custom-percentage'])) {
              print ' value="' . $_POST['custom-percentage'] . '"';
            }
          ?>
          />%</p>
      </div>
      <p class="split">Split: <input class="split" type="text" name="split" size="10" <?php
          if (isset($_POST['split'])) {
            print ' value="' . $_POST['split'] . '"';
          } else {
            print ' value="1"';
          }
        ?>
        /> person(s)</p>
      <div class="center">
        <p class="submit"><input type="submit" /></p>
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
      <div class="split-output">
        <p>Tip each: $<?php
            $split = (int)$_POST['split'];
            $tipEach = round($tip / $split, 2);
            print money_format('%.2n', $tipEach);
          ?>
        </p>
        <p>Total each: $<?php
            $totalEach = round($total / $split, 2);
            print money_format('%.2n', $totalEach);
          ?>
        </p>
      </div>
    </div>
  </div>
  </body>
</html>