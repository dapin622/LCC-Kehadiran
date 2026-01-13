<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>LCC MPR Kehadiran - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta20/dist/css/tabler.min.css" rel="stylesheet"/>
    <style>
      .brand {
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 15px;
        margin-left: 10px;
      }
      .brand img {
        height: 90px;
        margin-right: 20px;
        margin-top: -10px;
      }
      .brand h4 {
        margin: 0;
        font-weight: 700;
        line-height: 1.2;
        font-size: 30px;
      }
      .login-title {
        color: #000000ff; 
        font-weight: 600; 
       }
      .custom-label {
          font-family:'Inter Var', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;
          font-weight: 600;
      }

      .brand span {
        display: block;
        font-size: 18px;
        font-weight: normal;
        font-size: 30px;
      }
      .card-lg {
        max-width: 600px;
        margin: 100px auto;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);

      }
      .page-center {
        background-color: #ffffffff;
      }

    </style>
  </head>
  <body class="d-flex flex-column">

    <div class="page page-center">
      <div class="container container-tight py-4">

        <div class="card card-lg ">
          <div class="card-body">
            
            <div class="brand">
              <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/9/9e/Logo_of_People%27s_Consultative_Assembly_Indonesia.png/625px-Logo_of_People%27s_Consultative_Assembly_Indonesia.png"
                width="90" alt="MPR">
              <div>
                <h4><b>LCC MPR</b></h4>
                <span>KEHADIRAN</span>
              </div>
            </div><br>

            <h2 class="text-center login-title mb-4">Login to your account</h2>

            @if($errors->any())
              <div class="alert alert-danger">{{ $errors->first() }}</div>
            @endif

            <form action="{{ route('login') }}" method="POST">
              @csrf

              <div class="mb-3 text-start">
                <label class="form-label custom-label">Email address</label>
                <input type="email" name="email" class="form-control" placeholder="your@email.com" required>
              </div>

              <div class="mb-3 text-start">
                <label class="form-label custom-label">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Your Password" required>
              </div>

              <div class="form-footer">
                <button type="submit" class="btn btn-primary w-100">Sign in</button>
              </div>
            </form>

          </div>
        </div>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta20/dist/js/tabler.min.js"></script>
  </body>
</html>
