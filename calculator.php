
<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

session_start();


// Initialize calculator values
if (!isset($_SESSION["display"])) {
    $_SESSION["display"] = "0";
    $_SESSION["first"] = "";
    $_SESSION["operator"] = "";
    $_SESSION["new_number"] = false;
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $action = $_POST["action"] ?? "";

    // Clear calculator
    if ($action === "clear") {
        $_SESSION["display"] = "0";
        $_SESSION["first"] = "";
        $_SESSION["operator"] = "";
        $_SESSION["new_number"] = false;
    }

    // Number buttons
    elseif (strpos($action, "digit_") === 0) {
        $digit = substr($action, 6);

        if (preg_match('/^[0-9]$/', $digit)) {
            if (
                $_SESSION["new_number"] ||
                $_SESSION["display"] === "0" ||
                !is_numeric($_SESSION["display"])
            ) {
                $_SESSION["display"] = $digit;
                $_SESSION["new_number"] = false;
            } else {
                $_SESSION["display"] .= $digit;
            }
        }
    }

    // Decimal button
    elseif ($action === "decimal") {
        if ($_SESSION["new_number"]) {
            $_SESSION["display"] = "0.";
            $_SESSION["new_number"] = false;
        } elseif (
            is_numeric($_SESSION["display"]) &&
            strpos($_SESSION["display"], ".") === false
        ) {
            $_SESSION["display"] .= ".";
        }
    }

    // Delete last digit
    elseif ($action === "delete") {
        if (is_numeric($_SESSION["display"])) {
            if (strlen($_SESSION["display"]) > 1) {
                $_SESSION["display"] =
                    substr($_SESSION["display"], 0, -1);

                if (
                    $_SESSION["display"] === "-" ||
                    $_SESSION["display"] === ""
                ) {
                    $_SESSION["display"] = "0";
                }
            } else {
                $_SESSION["display"] = "0";
            }
        }
    }

    // Operator buttons
    elseif (strpos($action, "op_") === 0) {
        $operator = substr($action, 3);

        if (
            in_array($operator, ["+", "-", "*", "/", "%"], true) &&
            is_numeric($_SESSION["display"])
        ) {
            $_SESSION["first"] = $_SESSION["display"];
            $_SESSION["operator"] = $operator;
            $_SESSION["new_number"] = true;
        }
    }

    // Equals button
    elseif ($action === "equals") {
        if (
            $_SESSION["operator"] !== "" &&
            $_SESSION["first"] !== "" &&
            is_numeric($_SESSION["display"])
        ) {
            $a = (float) $_SESSION["first"];
            $b = (float) $_SESSION["display"];
            $operator = $_SESSION["operator"];
            $result = null;

            switch ($operator) {
                case "+":
                    $result = $a + $b;
                    break;

                case "-":
                    $result = $a - $b;
                    break;

                case "*":
                    $result = $a * $b;
                    break;

                case "/":
                    if ($b == 0) {
                        $result = "Cannot divide by zero";
                    } else {
                        $result = $a / $b;
                    }
                    break;

                case "%":
                    if ($b == 0) {
                        $result = "Cannot use MOD with zero";
                    } else {
                        $result = fmod($a, $b);
                    }
                    break;
            }

            if ($result !== null) {
                if (is_numeric($result)) {
                    // Remove unnecessary decimal zeros
                    $_SESSION["display"] =
                        rtrim(rtrim(sprintf("%.10f", $result), "0"), ".");

                    if (
                        $_SESSION["display"] === "" ||
                        $_SESSION["display"] === "-0"
                    ) {
                        $_SESSION["display"] = "0";
                    }
                } else {
                    $_SESSION["display"] = $result;
                }

                $_SESSION["first"] = "";
                $_SESSION["operator"] = "";
                $_SESSION["new_number"] = true;
            }
        }
    }
}

// Prepare display
$display = $_SESSION["display"];

if (
    $_SESSION["operator"] !== "" &&
    $_SESSION["first"] !== ""
) {
    $symbols = [
        "+" => "+",
        "-" => "−",
        "*" => "×",
        "/" => "÷",
        "%" => "MOD"
    ];

    $display = $_SESSION["new_number"]
        ? $_SESSION["first"] . " " .
          $symbols[$_SESSION["operator"]]
        : $_SESSION["first"] . " " .
          $symbols[$_SESSION["operator"]] . " " .
          $_SESSION["display"];
}

if (
    $_SERVER["REQUEST_METHOD"] === "POST" &&
    isset($_SERVER["HTTP_X_REQUESTED_WITH"]) &&
    $_SERVER["HTTP_X_REQUESTED_WITH"] === "XMLHttpRequest"
) {
    header("Content-Type: application/json");

    echo json_encode([
        "display" => $display
    ]);

    exit;
}

?>

