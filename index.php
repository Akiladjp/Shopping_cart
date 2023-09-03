<?php

session_start();

$connect = mysqli_connect("localhost", "root", "", "shopping_cart");

if(isset($_POST['add_to_cart'])) {

	if(isset($_SESSION['cart'])) {

		$session_array_id = array_column($_SESSION['cart'], "id");

		if(!in_array($_GET['id'], $session_array_id)) {

			$session_array = array(
			'id' => $_GET['id'],
			'name' => $_GET['name'],
			'price' => $_GET['price'],
			'quantity' => $_GET['quantity']
		);

		$_SESSION['cart'][] = $session_array;

		}
	}
	else {

		$session_array = array(
			'id' => $_GET['id'],
			'name' => $_GET['name'],
			'price' => $_GET['price'],
			'quantity' => $_GET['quantity']
		);

		$_SESSION['cart'][] = $session_array;
	}
}

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
	<title></title>
</head>
<body>

	<div class="container-fluid">
		<div class="col-md-12">
			<div class="row">
				<div class="col-md-6">
					<h2 class="text-center">Shopping Cart Data</h2>
					<div class="col-md-12">
						<div class="row">
							
						

					<?php

						$query = "SELECT * FROM cart_item";
						$result = mysqli_query($connect, $query);


						while ($row = mysqli_fetch_assoc($result)) { ?>
							<div class="col-md-4">		
								<form action="index.php?id=<?= $row['id'] ?>" method="post">
									<img src="img/<?= $row['image'] ?>" style='height: 100px;'>
									<h5 class="text-center"><?= $row['name'] ?></h5>
									<h5 class="text-center">Rs:<?= number_format($row['price'],2) ?></h5>
									<input type="hidden" name="name" value="<?= $row['name'] ?>">
									<input type="hidden" name="price" value="<?= $row['price'] ?>">
									<input type="number" name="quantity" value="1" class="form-control">
									<input type="submit" name="add_to_cart" class="btn btn-warning btn-block my-2" value="Add To Cart">
								</form>
							</div>
						<?php 
						}
					?>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<h2 class="text-center">Item Selected</h2>

					<?php


					$total = 0;

					$output = "";

					$output .= "

						<table class='table table-bordered table-striped'>
							<tr>
								<th>ID</th>
								<th>Name</th>
								<th>Price</th>
								<th>quantity</th>
								<th>Total-Price</th>
								<th>Action</th>
							<tr>
						</table>						


					";

					if(!empty($_SESSION['cart'])) {
						foreach ($_SESSION['cart'] as $key => $value) {
							
							$output .= "

								<tr>
									<th> ".$value['id']." </th>
									<th> ".$value['name']." </th>
									<th> ".$value['price']." </th>
									<th> ".$value['quantity']." </th>
									<th> ".number_format($value['Total-Price'] * $value['quantity'], 2)." </th>
									<th><a href='index.php?action=remove&id=".$value['id']."'><button class='btn btn-danger btn-block'>Remove</button></a></th>
								</tr>
							";


							$total = $total + $value['quantity'] * $value['price'];
						}

						$output .= "

							<tr>
								<td colspan='3'></td>
								<td><b>Total Price</b></td>
								<td>" .number_format($total, 2)."</td>

								<td>
									<a href='index.php?action=clearall'>
										<button class="btn btn-warning btn-block">Clear</button>
									</a>
								</td>
							</tr>

						";

					}	

					echo $output;
					?>

				</div>
			</div>
		</div>
	</div>


	<?php

	if(isset($_GET['action'])) {

		if(isset($_GET['action'] == "clearall")) {
			unset($_SESSION['cart']);
		}

		if($_GET['action'] == "remove") {

			foreach ($_SESSION['cart'] as $key => $value) {
				
				if($value['id'] == $_GET['id'] {
					unset($_SESSION['cart'][$key]);
				}
			}
		}
	}

	?>

</body>
</html>