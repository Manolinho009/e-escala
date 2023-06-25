
<?php 
include_once 'auth.php';

$user_name = $_SESSION["usr_name"];
if(!isset($user_name)){
  header('Location: sign-in.php');
}


try {
  $conn = new PDO('mysql:host=localhost;dbname=tb_esc', 'root', '');
  $data = $conn->query("SELECT * from tb_escala WHERE CAST(dh_escala as date) >= CAST( NOW() as date)");
  $day = $conn->query("SELECT *, CAST(dh_escala as TIME) as hr_escala from tb_escala WHERE CAST( dh_escala as date) = CAST( NOW() as date)");
  $conn = null;
} catch (PDOException $e) {
  print "Error!: " . $e->getMessage() . "<br/>";
  die();
}

$day_html = ["EDITOR"=>"","TRANSMICAO"=>"","STORY"=>"","FOTO"=>""];

foreach ($day as $key => $value) {
  if($value['de_escala'] == 'FOTO'){
    $day_html['FOTO'] .= "<h4 class='mb-0'>".$value['usr_nome'].' - '.$value['hr_escala'].'</h4>';
  }
  elseif($value['de_escala'] == 'STORY'){
    $day_html['STORY'] .= "<h4 class='mb-0'>".$value['usr_nome'].' - '.$value['hr_escala'].'</h4>';
  }
  elseif($value['de_escala'] == 'TRANSMICAO'){
    $day_html['TRANSMICAO'] .= "<h4 class='mb-0'>".$value['usr_nome'].' - '.$value['hr_escala'].'</h4>';
  }
  elseif($value['de_escala'] == 'EDITOR'){
    $day_html['EDITOR'] .= "<h4 class='mb-0'>".$value['usr_nome'].' - '.$value['hr_escala'].'</h4>';
  }
}




if(isset($_GET['query']) and $_GET['query'] == 'confirm'){
  $numero = $_GET['num_escala'];
  try {
    $conn = new PDO('mysql:host=localhost;dbname=tb_esc', 'root', '');
    $conn->query("
    UPDATE 
    tb_escala 
    SET 
    status='CONFIRMADO'
    WHERE num_escala = $numero
    ");
    $conn = null;
    header('Location: index.php');
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

<link rel="apple-touch-icon" sizes="76x76" href="./assets/img/apple-icon.png">
<link rel="icon" type="image/png" href="./assets/img/favicon.png">

<title>
E-Escala 
</title>



<!--     Fonts and icons     -->
<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700" />

<!-- Nucleo Icons -->
<link href="./assets/css/nucleo-icons.css" rel="stylesheet" />
<link href="./assets/css/nucleo-svg.css" rel="stylesheet" />

<!-- Font Awesome Icons -->
<script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>

<!-- Material Icons -->
<link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">

<!-- CSS Files -->



<link id="pagestyle" href="./assets/css/material-dashboard.css?v=3.1.0" rel="stylesheet" />

<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />


<style>
  .modal-backdrop{
    z-index: 0 !important;
    background-color: #00000000 !important;
  }
</style>


<!-- Nepcha Analytics (nepcha.com) -->
<!-- Nepcha is a easy-to-use web analytics. No cookies and fully compliant with GDPR, CCPA and PECR. -->
<script defer data-site="YOUR_DOMAIN_HERE" src="https://api.nepcha.com/js/nepcha-analytics.js"></script>


  </head>


  <body class="g-sidenav-show  bg-gray-100">
    

    

    
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

      <main class="main-content border-radius-lg ">
        <!-- Navbar -->

<nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" data-scroll="true">
  <div class="container-fluid py-1 px-3">
    <nav aria-label="breadcrumb">
      
      <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Pages</a></li>
        <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Principal</li>
      </ol>
      <h6 class="font-weight-bolder mb-0">Principal</h6>
      
    </nav>
    <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">

     
    </div>
  </div>
</nav>

<!-- End Navbar -->
<div class="container-fluid py-4">
                

<div class="row">
  <div class="col position-relative z-index-2">
    <div class="card card-plain mb-4">
      <div class="card-body p-3">
        <div class="row">
          <div class="col">
            <div class="d-flex flex-column h-100">
  <h2 class="font-weight-bolder mb-0">Escala de Hoje</h2>
</div>

          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-5 col-sm-5">
        <div class="card  mb-2">
  <div class="card-header p-3 pt-2">
    <div class="icon icon-lg icon-shape bg-gradient-dark shadow-dark shadow text-center border-radius-xl mt-n4 position-absolute">
      <i class="material-icons opacity-10">photo_camera</i>
    </div>
    <div class="text-end pt-1">
      <p class="text-sm mb-0 text-capitalize">Foto</p>
      <?php
        echo $day_html['FOTO'];
        
      ?>
    </div>
  </div>

  <hr class="dark horizontal my-0">

</div>

        <div class="card  mb-2">
  <div class="card-header p-3 pt-2">
    <div class="icon icon-lg icon-shape bg-gradient-primary shadow-primary shadow text-center border-radius-xl mt-n4 position-absolute">
      <i class="material-icons opacity-10">videocam</i>
    </div>
    <div class="text-end pt-1">
      <p class="text-sm mb-0 text-capitalize">Transmição</p>
      <?php
            echo $day_html['TRANSMICAO'];

      ?>
      </div>
  </div>

  <hr class="dark horizontal my-0">

</div>

      </div>
      <div class="col-lg-5 col-sm-5 mt-sm-0 mt-4">
        <div class="card  mb-2">
  <div class="card-header p-3 pt-2 bg-transparent">
    <div class="icon icon-lg icon-shape bg-gradient-success shadow-success text-center border-radius-xl mt-n4 position-absolute">
      <i class="material-icons opacity-10">smartphone</i>
    </div>
    <div class="text-end pt-1">
      <p class="text-sm mb-0 text-capitalize ">Story</p>
      <?php
        echo $day_html['STORY'];
      ?>
    </div>
  </div>

  <hr class="horizontal my-0 dark">

</div>

        <div class="card ">
  <div class="card-header p-3 pt-2 bg-transparent">
    <div class="icon icon-lg icon-shape bg-gradient-info shadow-info text-center border-radius-xl mt-n4 position-absolute">
      <i class="material-icons opacity-10">edit</i>
    </div>
    <div class="text-end pt-1">
      <p class="text-sm mb-0 text-capitalize ">Editor</p>
      <?php
        echo $day_html['EDITOR'];
      ?>
    </div>
  </div>

  <hr class="horizontal my-0 dark">
</div>

      </div>
    </div>

<div class="row mt-4">
  <div class="col-10">
    <div class="card mb-4 ">
    <div class="d-flex">
      <div class="icon icon-shape icon-lg bg-gradient-success shadow text-center border-radius-xl mt-n3 ms-4">
        <i class="material-icons opacity-10" aria-hidden="true">language</i>
      </div>
      <h6 class="mt-3 mb-2 ms-3 ">Proximas Escalas</h6>
    </div>


      <div class="card-body p-3">
        <div class="row">
          <div class="col-auto">
            <div class="table-responsive">
              <table class="table align-items-center ">
                <thead>
                  <th>Nome</th>
                  <th>Data</th>
                  <th>Função</th>
                  <th>status</th>
                  <th>ação</th>

                </thead>
                <tbody>
                    <?php
                      foreach ($data as $key => $value) {
                        # code...
                        $escala = $value['de_escala'];
                        $user = $value['usr_nome'];
                        $data = $value['dh_escala'];
                        $status = $value['status'];
                        $num_escala = $value['num_escala'];

                        if($escala == 'EDITOR'){
                          $imgEscala = 'edit_square';
                        }
                        elseif($escala == 'TRANSMICAO'){
                          $imgEscala = 'videocam';
                        }
                        elseif($escala == 'STORY'){
                          $imgEscala = 'smartphone';
                        }
                        elseif($escala == 'FOTO'){
                          $imgEscala = 'photo_camera';
                        }

                        $final = "
                          <tr>
                          
                        
                            <td class='w-30'>
                              <div class='d-flex px-2 py-1 align-items-center'>
                                <div>
                                  <span class='material-symbols-outlined'>
                                  $imgEscala
                                  </span>
                                </div>
                                <div class='ms-4'>
                                  <p class='text-xs font-weight-bold mb-0 '>Nome:</p>
                                  <h6 class='text-sm font-weight-normal mb-0 '>$user</h6>
                                </div>
                              </div>
                            </td>
                            <td>$data</td>
                            <td>$escala</td>
                            <td>$status</td>
                        ";
                        // <span class="material-symbols-outlined">
                        // videocam , smartphone , edit_square
                        // </span>
                        if($status == 'TROCA'){
                          echo $final . "<td><button type='button' data-bs-toggle='modal' data-bs-target='#modal-$num_escala' type='button' class='btn btn-danger' style='width: 100%' >Trocar</button>
                          <div class='modal fade'  id='modal-$num_escala' tabindex='-1' aria-labelledby='modal-$num_escala-Label' aria-hidden='true'>
                            <div class='modal-dialog'>
                              <div class='modal-content'>
                                <div class='modal-header'>
                                  <h5 class='modal-title' id='modal-$num_escala-Label'>Solicitar Troca de Escala</h5>
                                  <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                                </div>
                                <div class='modal-body'>
                                  <form>
                                    <div class='mb-3'>
                                    $num_escala    
                                    </div>
                                  </form>
                                </div>
                                <div class='modal-footer'>
                                  <button type='button' class='btn btn-danger' data-bs-dismiss='modal'>Close</button>
                                  <button type='button' class='btn btn-primary'>Send message</button>
                                </div>
                              </div>
                            </div>
                          </div>
                          </td></tr>";
                        }
                        elseif($status == 'NAO CONFIRMADO' && $user == $_SESSION['usr_name']){
                          echo $final."<td><button type='button' data-bs-toggle='modal'  data-bs-target='#modal-$num_escala' class='btn btn-success'  style='width: 100%'>CONFIRM.</button>
                          <div class='modal fade' id='modal-$num_escala' tabindex='-1' aria-labelledby='modal-$num_escala-Label' aria-hidden='true'>
                            <div class='modal-dialog'>
                              <div class='modal-content'>
                                <div class='modal-header'>
                                  <h5 class='modal-title' id='modal-$num_escala-Label'>Solicitar Troca de Escala</h5>
                                  <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                                </div>
                                <div class='modal-body'>
                                  <form>
                                    <div class='mb-3'>
                                    $num_escala    
                                    </div>
                                  </form>
                                </div>
                                <div class='modal-footer'>
                                  <button type='button' class='btn btn-danger' data-bs-dismiss='modal'>Trocar</button>
                                  <a href='?query=confirm' class='btn btn-success'>Confirmar</a>
                                </div>
                              </div>
                            </div>
                          </div>
                          </td></tr>";

                        }
                        elseif($user == $_SESSION['usr_name']){
                          echo $final.'<td><button type="button" disabled class="btn btn-success" style="width: 100%">CONFIRM.</button></td></tr>';

                        }
                        else{
                          echo $final.'<td></td></tr>';
                        }
                      }
                    
                    ?>

              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

      </div>
    </div>
  </div>
</div>




<!--   Core JS Files   -->
<script src="./assets/js/core/popper.min.js" ></script>
<script src="./assets/js/core/bootstrap.min.js" ></script>
<script src="./assets/js/plugins/perfect-scrollbar.min.js" ></script>
<script src="./assets/js/plugins/smooth-scrollbar.min.js" ></script>



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

<script src="./assets/js/material-dashboard.min.js?v=3.1.0"></script>
  </body>

</html>
