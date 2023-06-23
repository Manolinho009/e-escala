
<?php 
include_once 'auth.php';

$user_name = $_SESSION["usr_name"];
if(!isset($user_name)){
  header('Location: sign-in.php');
}


try {
  $conn = new PDO('mysql:host=localhost;dbname=tb_esc', 'root', '');
  $users = $conn->query("SELECT * FROM tb_login");
  $funcoes = $conn->query("SELECT * FROM tb_funcao");
  $escalas = $conn->query("SELECT * from tb_escala WHERE CAST(dh_escala as date) >= CAST( NOW() as date)");
  $escalas_old = $conn->query("SELECT * from tb_escala WHERE CAST(dh_escala as date) <= CAST( NOW() as date) LIMIT 15");
  $conn = null;
} catch (PDOException $e) {
  print "Error!: " . $e->getMessage() . "<br/>";
  die();
}


if(isset($_GET['delete'])){
  echo var_dump($_GET['delete']);
  $numero = $_GET['delete'];
  try {
    $conn = new PDO('mysql:host=localhost;dbname=tb_esc', 'root', '');
    $conn->query("DELETE FROM tb_escala WHERE num_escala = $numero");
    $conn = null;
    header('Location: new-escala.php');
  } catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
  }
}

if(isset($_POST['num_escala'])){
  echo var_dump($_POST['num_escala']);
  $numero = $_POST['num_escala'];
  $de_escala = $_POST['funcao'];
  $dh_escala = $_POST['data'];
  $usr_nome = $_POST['nome'];
  try {
    $conn = new PDO('mysql:host=localhost;dbname=tb_esc', 'root', '');
    $conn->query("
    UPDATE 
    tb_escala 
    SET 
    de_escala='$de_escala'
    ,dh_escala='$dh_escala'
    ,usr_nome='$usr_nome'
    ,status='NAO CONFIRMADO'
    WHERE num_escala = $numero
    ");
    $conn = null;
    header('Location: new-escala.php');
  } catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
  }
}elseif(isset($_POST['data'])){
  $de_escala = $_POST['funcao'];
  $dh_escala = $_POST['data'];
  $usr_nome = $_POST['nome'];
  try {
    $conn = new PDO('mysql:host=localhost;dbname=tb_esc', 'root', '');
    $conn->query("
    INSERT INTO 
    tb_escala(de_escala,dh_escala,usr_nome,status)
    VALUES('$de_escala','$dh_escala','$usr_nome','NAO CONFIRMADO')");
    $conn = null;
    header('Location: new-escala.php');
  } catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
  }
}


?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../assets/img/favicon.png">
  <title>
   Gerenciar Escalas
  </title>
  <!--     Fonts and icons     -->
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700" />
  <!-- Nucleo Icons -->
  <link href="../assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <!-- Material Icons -->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
  <!-- CSS Files -->
  <link id="pagestyle" href="../assets/css/material-dashboard.css?v=3.1.0" rel="stylesheet" />
  <!-- Nepcha Analytics (nepcha.com) -->
  <!-- Nepcha is a easy-to-use web analytics. No cookies and fully compliant with GDPR, CCPA and PECR. -->
  <script defer data-site="YOUR_DOMAIN_HERE" src="https://api.nepcha.com/js/nepcha-analytics.js"></script>
</head>

<body class="g-sidenav-show  bg-gray-200">
<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3   bg-gradient-dark" id="sidenav-main">

<div class="sidenav-header">
  <i class="fas fa-times p-3 cursor-pointer text-white opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
  <a class="navbar-brand m-0" href=" https://demos.creative-tim.com/material-dashboard/pages/dashboard " target="_blank">
    <img src="./assets/img/logo-ct.png" class="navbar-brand-img h-100" alt="main_logo">
    <span class="ms-1 font-weight-bold text-white">E-Escala</span>
  </a>
</div>


<hr class="horizontal light mt-0 mb-2">

<div class="collapse navbar-collapse  w-auto " id="sidenav-collapse-main">
  <ul class="navbar-nav">
    
<li class="nav-item">
<a class="nav-link text-white " href="/">
  
    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
      <i class="material-icons opacity-10">dashboard</i>
    </div>
  
  <span class="nav-link-text ms-1">Principal</span>
</a>
</li>

<?php
if ($_SESSION['usr_codfunc'] >= 3){
echo 
'<li class="nav-item">
  <a class="nav-link text-white " href="./new-escala.php">
    
      <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
        <i class="material-icons opacity-10">linear_scale</i>
      </div>
    
    <span class="nav-link-text ms-1">Nova Escala</span>
  </a>
</li>
';
}

?>

  </ul>
</div>

</div>

</aside>

  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
    
    <div class="container-fluid py-4">
      
      <div class="row">
        <div class="col-md-7 mt-4">
          <div class="card">
            <div class="card-header pb-0 px-3">
              <h6 class="mb-0">Escalas</h6>
            </div>
            <div class="card-body pt-4 p-3">
            <div class="container mb-3">
              <form action="/new-escala.php" method="POST">
                <div class="row">
                  <div class="col-3">
                    <label for="exampleDataList" class="form-label">Nome</label>
                      <?php 
                        if(isset($_GET['edit'])){
                          $nome = $_GET['nome'];
                          $num_escala = $_GET['edit'];
                          echo "<input class='form-control' hidden name='num_escala' value='$num_escala'>";
                          echo "<input class='form-control' value='$nome' name='nome' list='listaUsuarios' id='exampleDataList' placeholder='Nome da Pessoa...'>";
                          echo "<datalist id='listaUsuarios'>";
                          foreach ($users as $key => $value) {
                            $user_name = $value['usr_nome']; 
                            echo "<option value='$user_name'>";
                          }
                        }
                        else{
                          foreach ($users as $key => $value) {
                            echo "<input class='form-control' name='nome' list='listaUsuarios' id='exampleDataList' placeholder='Nome da Pessoa...'>";
                            echo "<datalist id='listaUsuarios'>";
                            $user_name = $value['usr_nome']; 
                            echo "<option value='$user_name'>";
                          }
                        }
                      ?>
                    </datalist>
                  </div>

                  <div class="col-3">
                    <label for="exampleDataList" class="form-label">Função</label>
                    
                      <?php 
                        if(isset($_GET['edit'])){
                          $funcao = $_GET['funcao'];
                          echo "<input class='form-control' value='$funcao' name='funcao' list='listaFuncoes' id='exampleDataList' placeholder='Função a ser exercida...'>";
                          echo "<datalist id='listaFuncoes'>";
                          foreach ($funcoes as $key => $value) {
                            $funcao_nome = $value['de_funcao']; 
                            echo "<option value='$funcao_nome'>";
                          }
                        }
                        else{
                          echo "<input class='form-control' name='funcao' list='listaFuncoes' id='exampleDataList' placeholder='Função a ser exercida...'>";
                          echo "<datalist id='listaFuncoes'>";
                          foreach ($funcoes as $key => $value) {
                            $funcao_nome = $value['de_funcao']; 
                            echo "<option value='$funcao_nome'>";
                          }
                        }
                      ?>
                    </datalist>
                  </div>
                  <div class="col-3">
                    <label for="exampleDataList" class="form-label">Data</label>
                    <?php if(isset($_GET['edit'])){
                      $data = $_GET['data'];
                      echo "<input class='form-control' value='$data' name='data' type='datetime-local' placeholder='Data da escala...'>";
                    }else{
                      echo "<input class='form-control' name='data' type='datetime-local' placeholder='Data da escala...'>";
                    } 
                    ?>
                    
                  </div>
                  <div class="col-3">
                    <button type="submit" class="btn btn-danger mt-4">salvar</button>
                  </div>
                </div>


              </form>
            </div>

              <ul class="list-group">

                <?php 
                
                foreach ($escalas as $key => $value) {
                  # code...
                  $nome = $value['usr_nome'];
                  $funcao = $value['de_escala'];
                  $data = $value['dh_escala'];
                  $status = $value['status'];
                  $num_escala = $value['num_escala'];

                  echo "<li class='list-group-item border-0 d-flex p-4 mb-2 bg-gray-100 border-radius-lg'>
                    <div class='d-flex flex-column'>
                      <h6 class='mb-3 text-sm'>$nome</h6>
                      <span class='mb-2 text-xs'>Funcao: <span class='text-dark font-weight-bold ms-sm-2'>$funcao</span></span>
                      <span class='mb-2 text-xs'>Data: <span class='text-dark ms-sm-2 font-weight-bold'>$data</span></span>
                      <span class='text-xs'>Status: <span class='text-dark ms-sm-2 font-weight-bold'>$status</span></span>
                    </div>
                    <div class='ms-auto text-end'>
                      <a class='btn btn-link text-danger text-gradient px-3 mb-0' href='new-escala.php?delete=$num_escala'><i class='material-icons text-sm me-2'>delete</i>Delete</a>
                      <a class='btn btn-link text-dark px-3 mb-0' href='new-escala.php?edit=$num_escala&nome=$nome&funcao=$funcao&data=$data'><i class='material-icons text-sm me-2'>edit</i>Edit</a>
                    </div>
                  </li>";
                }
                
                ?>

              </ul>
            </div>
          </div>
        </div>
        <div class="col-md-5 mt-4">
          <div class="card h-100 mb-4">
            <div class="card-header pb-0 px-3">
              <div class="row">
                <div class="col-md-6">
                  <h6 class="mb-0">Escalas Passadas</h6>
                </div>
                <div class="col-md-6 d-flex justify-content-start justify-content-md-end align-items-center">
                  <i class="material-icons me-2 text-lg">date_range</i>
                  <small>15 Dias atras</small>
                </div>
              </div>
            </div>
            <div class="card-body pt-4 p-3">
              <h6 class="text-uppercase text-body text-xs font-weight-bolder mb-3">Escalas</h6>
              <ul class="list-group">
                
              <?php 
                foreach ($escalas_old as $key => $value) {
                  # code...
                  $nome = $value['usr_nome'];
                  $funcao = $value['de_escala'];
                  $data = $value['dh_escala'];
                  $status = $value['status'];
                  $num_escala = $value['num_escala'];

                  echo "
                  <li class='list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg'>
                      <div class='d-flex align-items-center'>
                        <button class='btn btn-icon-only btn-rounded btn-outline-danger mb-0 me-3 p-3 btn-sm d-flex align-items-center justify-content-center'><i class='material-icons text-lg'>expand_more</i></button>
                        <div class='d-flex flex-column'>
                          <h6 class='mb-1 text-dark text-sm'>$funcao</h6>
                          <span class='text-xs'>$nome - $data</span>
                        </div>
                      </div>
                  </li> 
                  ";
                }
              ?>

              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
  <div class="fixed-plugin">
    <a class="fixed-plugin-button text-dark position-fixed px-3 py-2">
      <i class="material-icons py-2">settings</i>
    </a>
    <div class="card shadow-lg">
      <div class="card-header pb-0 pt-3">
        <div class="float-start">
          <h5 class="mt-3 mb-0">Material UI Configurator</h5>
          <p>See our dashboard options.</p>
        </div>
        <div class="float-end mt-4">
          <button class="btn btn-link text-dark p-0 fixed-plugin-close-button">
            <i class="material-icons">clear</i>
          </button>
        </div>
        <!-- End Toggle Button -->
      </div>
      <hr class="horizontal dark my-1">
      <div class="card-body pt-sm-3 pt-0">
        <!-- Sidebar Backgrounds -->
        <div>
          <h6 class="mb-0">Sidebar Colors</h6>
        </div>
        <a href="javascript:void(0)" class="switch-trigger background-color">
          <div class="badge-colors my-2 text-start">
            <span class="badge filter bg-gradient-primary active" data-color="primary" onclick="sidebarColor(this)"></span>
            <span class="badge filter bg-gradient-dark" data-color="dark" onclick="sidebarColor(this)"></span>
            <span class="badge filter bg-gradient-info" data-color="info" onclick="sidebarColor(this)"></span>
            <span class="badge filter bg-gradient-success" data-color="success" onclick="sidebarColor(this)"></span>
            <span class="badge filter bg-gradient-warning" data-color="warning" onclick="sidebarColor(this)"></span>
            <span class="badge filter bg-gradient-danger" data-color="danger" onclick="sidebarColor(this)"></span>
          </div>
        </a>
        <!-- Sidenav Type -->
        <div class="mt-3">
          <h6 class="mb-0">Sidenav Type</h6>
          <p class="text-sm">Choose between 2 different sidenav types.</p>
        </div>
        <div class="d-flex">
          <button class="btn bg-gradient-dark px-3 mb-2 active" data-class="bg-gradient-dark" onclick="sidebarType(this)">Dark</button>
          <button class="btn bg-gradient-dark px-3 mb-2 ms-2" data-class="bg-transparent" onclick="sidebarType(this)">Transparent</button>
          <button class="btn bg-gradient-dark px-3 mb-2 ms-2" data-class="bg-white" onclick="sidebarType(this)">White</button>
        </div>
        <p class="text-sm d-xl-none d-block mt-2">You can change the sidenav type just on desktop view.</p>
        <!-- Navbar Fixed -->
        <div class="mt-3 d-flex">
          <h6 class="mb-0">Navbar Fixed</h6>
          <div class="form-check form-switch ps-0 ms-auto my-auto">
            <input class="form-check-input mt-1 ms-auto" type="checkbox" id="navbarFixed" onclick="navbarFixed(this)">
          </div>
        </div>
        <hr class="horizontal dark my-3">
        <div class="mt-2 d-flex">
          <h6 class="mb-0">Light / Dark</h6>
          <div class="form-check form-switch ps-0 ms-auto my-auto">
            <input class="form-check-input mt-1 ms-auto" type="checkbox" id="dark-version" onclick="darkMode(this)">
          </div>
        </div>
        <hr class="horizontal dark my-sm-4">
        <a class="btn bg-gradient-info w-100" href="https://www.creative-tim.com/product/material-dashboard-pro">Free Download</a>
        <a class="btn btn-outline-dark w-100" href="https://www.creative-tim.com/learning-lab/bootstrap/overview/material-dashboard">View documentation</a>
        <div class="w-100 text-center">
          <a class="github-button" href="https://github.com/creativetimofficial/material-dashboard" data-icon="octicon-star" data-size="large" data-show-count="true" aria-label="Star creativetimofficial/material-dashboard on GitHub">Star</a>
          <h6 class="mt-3">Thank you for sharing!</h6>
          <a href="https://twitter.com/intent/tweet?text=Check%20Material%20UI%20Dashboard%20made%20by%20%40CreativeTim%20%23webdesign%20%23dashboard%20%23bootstrap5&amp;url=https%3A%2F%2Fwww.creative-tim.com%2Fproduct%2Fsoft-ui-dashboard" class="btn btn-dark mb-0 me-2" target="_blank">
            <i class="fab fa-twitter me-1" aria-hidden="true"></i> Tweet
          </a>
          <a href="https://www.facebook.com/sharer/sharer.php?u=https://www.creative-tim.com/product/material-dashboard" class="btn btn-dark mb-0 me-2" target="_blank">
            <i class="fab fa-facebook-square me-1" aria-hidden="true"></i> Share
          </a>
        </div>
      </div>
    </div>
  </div>
  <!--   Core JS Files   -->
  <script src="../assets/js/core/popper.min.js"></script>
  <script src="../assets/js/core/bootstrap.min.js"></script>
  <script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="../assets/js/plugins/smooth-scrollbar.min.js"></script>
  <script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
      var options = {
        damping: '0.5'
      }
      Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
  </script>
  <!-- Github buttons -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="../assets/js/material-dashboard.min.js?v=3.1.0"></script>
</body>

</html>