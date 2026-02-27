<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Page not found</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body{font-family:"Space Grotesk",system-ui,-apple-system,Segoe UI,Arial,sans-serif;background:linear-gradient(180deg,#eef2f7 0%,#f8fafc 60%,#ffffff 100%);}
    .card{border-radius:22px;box-shadow:0 10px 30px rgba(2,6,23,.08);}
    .btn{border-radius:999px;padding:8px 16px;font-weight:600;}
  </style>
</head>
<body>
<div class="container py-5">
  <div class="card">
    <div class="card-body text-center">
      <h1 class="display-6">404</h1>
      <p class="text-muted mb-3">This page doesn’t exist or was moved.</p>
      <a class="btn btn-primary" href="{{ route('blog.index') }}">Back to Home</a>
    </div>
  </div>
</div>
</body>
</html>
