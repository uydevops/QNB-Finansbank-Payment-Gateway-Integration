<?php
require_once 'SipaySmart3D.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sipaySmart3d = new SipaySmart3D();
    $sipaySmart3d->paySmart3D($_POST);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Checkout</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <style>
        form {
            padding: 30px 10px 10px;
            margin-top: 15px;
            -webkit-box-shadow: -1px 6px 15px -10px rgba(145, 145, 145, 1);
            -moz-box-shadow: -1px 6px 15px -10px rgba(145, 145, 145, 1);
            box-shadow: -1px 6px 15px -10px rgba(145, 145, 145, 1);
        }

        input[type=checkbox].css-checkbox:checked + label.css-label {
            background-position: 0 -15px;
        }
        input[type=checkbox].css-checkbox + label.css-label {
            padding-left: 5px;
            height: 15px;
            display: inline-block;
            line-height: 15px;
            background-repeat: no-repeat;
            background-position: 0 0;
            font-size: 14px;
            vertical-align: middle;
            cursor: pointer;
        }
        label {
            font-weight: 500 !important;
            color: black;
        }
    </style>
</head>
<body>

<div class="modal fade" id="checkoutModal" tabindex="-1" role="dialog" aria-labelledby="checkoutModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="checkoutModalLabel">Checkout</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="<?php echo($_SERVER['PHP_SELF']) ?>" class="payment_form" method="post">
                    <div class="form-group">
                        <label>Card Holder Name</label>
                        <input type="text" class="form-control" id="card_holder_name" name="cc_holder_name"  placeholder="Card Holder" required>
                    </div>
                    <div class="form-group">
                        <label>4022780520669303</label>
                        <input type="text" class="form-control number-only" id="card" name="cc_no" value="" placeholder="Card Number" required>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label>Expiry Year</label>
                            <select class="form-control" name="expiry_year" required>
                                <?php
                                for ($i = date('Y'); $i <= date('Y') + 10; $i++) {
                                    echo '<option value="' . $i . '">' . $i . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Expiry Month</label>
                            <select class="form-control" name="expiry_month" required>
                                <?php
                                for ($i = 1; $i <= 12; $i++) {
                                    echo '<option value="' . $i . '">' . $i . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Installment</label>
                            <input class="form-control number-only" id="installments_number" type="text" required name="installments_number" value="1" placeholder="Installment">
                        </div>
                        <div class="form-group col-md-6">
                            <label>CVV</label>
                            <input class="form-control number-only" id="securitycode" type="text" required name="cvv" placeholder="CVV">
                        </div>
                    </div>
                    <hr>
                    <div class="form-group">
                        <input type="submit" class="btn btn-primary btn-block" value="Pay">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function(){
        $('#checkoutModal').modal('show');
    });

    $(".number-only").keypress(function(e){
        var keyCode = e.which;
        if (keyCode < 48 || keyCode > 57) {
            return false;
        }
    });

    $('#recurringPayment').click(function() {
        if ($(this).is(':checked')) {
            $("#recurringArea").show(100);
            $("#recurringArea :input").each(function() {
                $(this).prop('required', true);
            });
        } else {
            $("#recurringArea").hide(100);
            $("#recurringArea :input").each(function() {
                $(this).prop('required', false);
            });
        }
    });
</script>
</body>
</html>
