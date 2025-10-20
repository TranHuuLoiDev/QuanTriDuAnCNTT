<?php  
session_start();

if (isset($_SESSION['user_id']) && isset($_SESSION['user_email'])) {
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Add Author</title>

	<!-- Bootstrap -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"></script>

	<!-- Custom Style -->
	<style>
		body {
			background: linear-gradient(135deg, #74ABE2, #5563DE);
			min-height: 100vh;
			font-family: 'Poppins', sans-serif;
		}
		.navbar {
			background: rgba(255, 255, 255, 0.9);
			box-shadow: 0 2px 8px rgba(0,0,0,0.1);
			border-radius: 10px;
			margin-top: 10px;
		}
		.navbar-brand {
			font-weight: bold;
			color: #3347B0 !important;
		}
		.nav-link {
			color: #333 !important;
			font-weight: 500;
			transition: 0.3s;
		}
		.nav-link:hover {
			color: #007bff !important;
			transform: translateY(-2px);
		}
		form {
			background: #fff;
			border-radius: 15px;
			padding: 30px;
			box-shadow: 0 4px 12px rgba(0,0,0,0.2);
			margin: 50px auto;
			color: #333;
			max-width: 600px;
		}
		h1 {
			font-weight: 600;
			color: #3347B0;
		}
		.form-label {
			font-weight: 500;
			color: #333;
		}
		.btn-primary {
			background: #5563DE;
			border: none;
			transition: 0.3s;
			font-weight: 500;
		}
		.btn-primary:hover {
			background: #3347B0;
			transform: scale(1.05);
		}
		.alert {
			border-radius: 10px;
			font-weight: 500;
		}
		footer {
			text-align: center;
			margin-top: 50px;
			color: #fff;
			font-weight: 500;
		}
	</style>
</head>
<body>
	<div class="container">
		<nav class="navbar navbar-expand-lg navbar-light mt-3">
		  <div class="container-fluid">
		    <a class="navbar-brand" href="admin.php">Admin Panel</a>
		    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
		      <span class="navbar-toggler-icon"></span>
		    </button>
		    <div class="collapse navbar-collapse" id="navbarNav">
		      <ul class="navbar-nav ms-auto">
		        <li class="nav-item"><a class="nav-link" href="index.php">Store</a></li>
		        <li class="nav-item"><a class="nav-link" href="add-book.php">Add Book</a></li>
		        <li class="nav-item"><a class="nav-link" href="add-category.php">Add Category</a></li>
		        <li class="nav-item"><a class="nav-link active text-primary fw-bold" href="add-author.php">Add Author</a></li>
		        <li class="nav-item"><a class="nav-link text-danger" href="logout.php">Logout</a></li>
		      </ul>
		    </div>
		  </div>
		</nav>

		<form action="php/add-author.php" method="post" class="mt-5">
			<h1 class="text-center mb-4">✍️ Add New Author</h1>

			<?php if (isset($_GET['error'])) { ?>
			  <div class="alert alert-danger"><?=htmlspecialchars($_GET['error']); ?></div>
			<?php } ?>
			<?php if (isset($_GET['success'])) { ?>
			  <div class="alert alert-success"><?=htmlspecialchars($_GET['success']); ?></div>
			<?php } ?>

			<div class="mb-3">
			    <label class="form-label">Author Name</label>
			    <input type="text" class="form-control" name="author_name" placeholder="Enter author name...">
			</div>

			<div class="mb-3">
			    <label class="form-label">Author Biography (optional)</label>
			    <textarea class="form-control" name="author_bio" rows="3" placeholder="Short bio or background..."></textarea>
			</div>

		    <button type="submit" class="btn btn-primary w-100 py-2 mt-3">Add Author</button>
		</form>

		<footer>© 2025 Online Book Store — Designed with 💙</footer>
	</div>
</body>
</html>

<?php }else{
  header("Location: login.php");
  exit;
} ?>
