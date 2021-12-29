<!-- 
    This is the dashboard
    - if user is not logged in -> redirect to account
    - if user is logged in -> proceed

-->
<?php 
    include_once('php/db.php'); 

    $loggedin = false;
    if(isset($_SESSION["username"])) {
        $username = $_SESSION["username"];
        $loggedin = true;
    } else {
      header("Location: index.php");
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $data = json_decode(json_encode($_POST), true);

        $amount = htmlspecialchars(trim($data["amount"]));
        $type = htmlspecialchars(trim($data["type"]));
        $date = date(htmlspecialchars(trim($data["date"])));
        $description = htmlspecialchars(trim($data["description"]));

        $statement = $conn->prepare("INSERT INTO expenses (username, date, amount, description, type) VALUES (?,?,?,?,?)");
        $statement->execute([$username, $date, $amount, $description, $type]);

        $success = true;
    }

    if($loggedin) {
        $query = $conn->prepare("SELECT * FROM expenses WHERE username = ? ORDER BY date DESC");
        $query->execute([$username]);
        $expenses = $query->fetchAll(PDO::FETCH_NAMED);
    }    
?>
<link rel="stylesheet" href="css/index.css">
<link rel="stylesheet" href="css/datepicker.css">
<script src="js/jquery.min.js"></script>
<script src="js/datepicker.js"></script>
<?php
    include_once('components/top.html');
    include_once('components/navbar.php');
?>

<main class="container mt-2">
    <div class="row">
        <div class="col-12 col-md-4">
            <div class="card">
                <div class="card-header">
                    New Record
                </div>
                <div class="card-body">
                    <?php if(isset($success)){ ?>
                        <div class="alert alert-success" role="alert">Record saved!</div>
                    <?php } ?>
                    <form class="row g-3" method="POST">
                        <div class="col-md-6">
                            <label for="inAmount" class="form-label">Amount</label>
                            <input name="amount" required type="number" min="1" class="form-control" id="inAmount" placeholder="ex: 100">
                        </div>
                        <div class="col-md-6">
                            <label for="inType" class="form-label">Type</label>
                            <select name="type" required id="inType" class="form-select">
                                <option value="income" selected>Income</option>
                                <option value="expense">Expense</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label for="inDate" class="form-label">Date</label>
                            <input name="date" type="date" class="form-control" id="inDate" max="<?= date('Y-m-d'); ?>">
                        </div>
                        <div class="col-12">
                            <label for="inDescription" class="form-label">Description</label>
                            <textarea name="description" type="text" class="form-control" id="inputDescription" placeholder="Brief description"></textarea>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="w-100 btn btn-outline-success">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-8">

        <br>
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Dashboard</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                  <div class="btn-group me-2">
                    <ul class="nav nav-pills" id="pills-tab" role="tablist">
                      <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#tab-summary" type="button" role="tab">Summary</button>
                      </li>
                      <li class="nav-item" role="presentation">
                        <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#tab-detailed" type="button" role="tab">Detailed</button>
                      </li>
                    </ul>
                  </div>
                                
                </div>
            </div>

            <div class="tab-content" id="pills-tabContent">
              <div class="tab-pane fade show active" id="tab-summary" role="tabpanel">
                <canvas id="myChart" width="200" height="200"></canvas>
              </div>
              <div class="tab-pane fade" id="tab-detailed" role="tabpanel">
                <div class="table-responsive">
                    <table class="table table-striped table-sm">
                    <thead>
                        <tr>
                            <th scope="col">Amount</th>
                            <th scope="col">Date</th>
                            <th scope="col">Type</th>
                            <th scope="col">Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($expenses as $expense){ ?>
                            <tr>
                                <td><?= $expense["amount"] ?></td>
                                <td><?php echo date_format(date_create($expense["date"]), "d M Y"); ?></td>
                                <td><?= $expense["type"] ?></td>
                                <td><?= $expense["description"] ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                    </table>
                </div>
              </div>
            </div>


        </div>
    </div>
<main>

<script src="js/datepicker.en.js"></script>
<script src="js/chart.min.js"></script>
<script>
  $('#dp').datepicker()
</script>
<script>
    const ctx = document.getElementById('myChart').getContext('2d');
    let incomes = 0;
    let expenses = 0;
    let records = <?php print_r(json_encode($expenses)); ?>;

    for (const index in records) {
        let record = records[index];
        
        if(record.type == "income") {
            incomes += +record.amount;
        } else {
            expenses += +record.amount;
        }
    }

    const data = {
        labels: [
            'Income: Rs. ' + incomes,
            'Expense: Rs. ' + expenses,
        ],
        datasets: [{
            data: [incomes, expenses],
            backgroundColor: [
                'rgb(255, 99, 132)',
                'rgb(54, 162, 235)',
            ],
            hoverOffset: 4
        }]
    };
    const config = {
        type: 'doughnut',
        data: data,
    };
    const myChart = new Chart(ctx, config);
</script>

<?php include_once('components/bottom.html'); ?>