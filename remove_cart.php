<?php
session_start();
$i = $_GET['i'];
unset($_SESSION['cart'][$i]);
$_SESSION['cart'] = array_values($_SESSION['cart']);
header("Location: cart.php");
