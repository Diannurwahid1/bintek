<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Dashboard - RSUD Dayaku Raja</title>

    <!-- Bootstrap Core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="vendor/metisMenu/metisMenu.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="dist/css/sb-admin-2.css" rel="stylesheet">

    <!-- Morris Charts CSS -->
    <link href="vendor/morrisjs/morris.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

    <div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.php">
                    <img src="https://dayakuraja.id/LandingPage/img/logodara.png" alt="RSUD Dayaku Raja" style="height: 30px; margin-right: 10px; display: inline-block;">
                    RSUD Dayaku Raja
                </a>
            </div>
            <!-- /.navbar-header -->

            <ul class="nav navbar-top-links navbar-right">
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-user fa-fw"></i> <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li><a href="#"><i class="fa fa-user fa-fw"></i> User Profile</a>
                        </li>
                        <li><a href="#"><i class="fa fa-gear fa-fw"></i> Settings</a>
                        </li>
                        <li class="divider"></li>
                        <li><a href="login.html"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                        </li>
                    </ul>
                    <!-- /.dropdown-user -->
                </li>
                <!-- /.dropdown -->
            </ul>
            <!-- /.navbar-top-links -->

            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                        <li class="sidebar-search">
                            <div class="input-group custom-search-form">
                                <input type="text" class="form-control" placeholder="Search...">
                                <span class="input-group-btn">
                                <button class="btn btn-default" type="button">
                                    <i class="fa fa-search"></i>
                                </button>
                            </span>
                            </div>
                            <!-- /input-group -->
                        </li>
                        <li class="active">
                            <a href="index.php"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a>
                        </li>
                        <li>
                            <a href="import.html"><i class="fa fa-upload fa-fw"></i> Import Data</a>
                        </li>
                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->
        </nav>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-8">
                    <h1 class="page-header">Dashboard</h1>
                </div>
            <div class="col-lg-4">
                <div class="panel panel-default" style="margin-top: 20px;">
                    <div class="panel-body">
                        <div class="form-inline">
                            <label>Filter Periode:</label>
                            <input type="date" id="startDate" class="form-control" style="margin: 0 5px;">
                            <span>s/d</span>
                            <input type="date" id="endDate" class="form-control" style="margin: 0 5px;">
                            <button id="filterBtn" class="btn btn-primary btn-sm">
                                <i class="fa fa-filter"></i> Filter
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
        <div class="row">
            <div class="col-lg-3 col-md-6">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa fa-users fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge" id="bpjs_clear_today">-</div>
                                <div>Pasien BPJS Clear Hari Ini</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="panel panel-green">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa fa-check-square-o fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge" id="klaim_clear_today">-</div>
                                <div>Klaim Clear Hari Ini</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="panel panel-yellow">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa fa-clock-o fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge" id="klaim_pending_today">-</div>
                                <div>Klaim Pending Hari Ini</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="panel panel-red">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa fa-smile-o fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge" id="survey_today">-</div>
                                <div>Survey Kepuasan Hari Ini</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.row -->
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">Pasien BPJS Clear per Hari</div>
                    <div class="panel-body"><canvas id="bpjsChart"></canvas></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-success">
                    <div class="panel-heading">Status Klaim Pasien</div>
                    <div class="panel-body"><canvas id="klaimChart"></canvas></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-warning">
                    <div class="panel-heading">Kepuasan Pasien per Hari</div>
                    <div class="panel-body"><canvas id="kepuasanChart"></canvas></div>
                </div>
            </div>
        </div>
        <!-- /.row -->
        </div>
        <!-- /#page-wrapper -->
    </div>
    <!-- /#wrapper -->

    <!-- jQuery -->
    <script src="vendor/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="vendor/metisMenu/metisMenu.min.js"></script>

    <!-- Chart.js JavaScript from CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
    #dropZone:hover {
        border-color: #007bff !important;
        background-color: #f8f9fa;
        cursor: pointer;
    }
    .table th {
        background-color: #f5f5f5;
    }
    </style>

    <!-- Custom Theme JavaScript -->
    <script src="dist/js/sb-admin-2.js"></script>
    <script>
    $(function(){
        // Initialize Chart.js global config
        Chart.defaults.responsive = true;
        Chart.defaults.maintainAspectRatio = false;
        
        // Set default dates to today only
        const today = new Date().toISOString().split('T')[0];
        $('#startDate').val(today);
        $('#endDate').val(today);
        
        // Initialize chart variables
        window.bpjsChart = null;
        window.klaimChart = null;
        window.kepuasanChart = null;
        
        // Load initial data
        loadDashboardData();
        
        // Filter button event
        $('#filterBtn').on('click', function() {
            const startDate = $('#startDate').val();
            const endDate = $('#endDate').val();
            
            if (!startDate || !endDate) {
                alert('Pilih tanggal mulai dan akhir terlebih dahulu!');
                return;
            }
            
            if (startDate > endDate) {
                alert('Tanggal mulai tidak boleh lebih besar dari tanggal akhir!');
                return;
            }
            
            loadDashboardData();
        });
        
        // Auto filter when date changes
        $('#startDate, #endDate').on('change', function() {
            const startDate = $('#startDate').val();
            const endDate = $('#endDate').val();
            
            if (startDate && endDate && startDate <= endDate) {
                loadDashboardData();
            }
        });
        
        function loadDashboardData() {
            const startDate = $('#startDate').val();
            const endDate = $('#endDate').val();
            const dateParams = `start_date=${startDate}&end_date=${endDate}`;
            
            // Summary Card
            $.getJSON(`api_summary.php?${dateParams}`, function(data){
                $('#bpjs_clear_today').text(data.bpjs_clear_today);
                $('#klaim_clear_today').text(data.klaim_clear_today);
                $('#klaim_pending_today').text(data.klaim_pending_today);
                $('#survey_today').text(data.survey_today);
            }).fail(function(xhr, status, error) {
                console.error('Summary API Error:', error);
                $('#bpjs_clear_today, #klaim_clear_today, #klaim_pending_today, #survey_today').text('Error');
            });
            
            // BPJS Chart
            $.getJSON(`api_dashboard.php?action=jumlah_pasien_bpjs&${dateParams}`, function(data){
                console.log('BPJS Chart Data:', data);
                
                if (!data || data.length === 0) {
                    console.warn('No BPJS data found');
                    // Show empty chart with message
                    if (window.bpjsChart) {
                        window.bpjsChart.destroy();
                        window.bpjsChart = null;
                    }
                    const ctx1Empty = document.getElementById('bpjsChart').getContext('2d');
                    window.bpjsChart = new Chart(ctx1Empty, {
                        type: 'bar',
                        data: { labels: ['No Data'], datasets: [{ label: 'Jumlah', data: [0], backgroundColor:'#ccc' }] },
                        options: { 
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: { legend: { display: false } }
                        }
                    });
                    return;
                }
                
                const labels = data.map(x=>x.tgl_verifikasi);
                const values = data.map(x=>parseInt(x.jumlah));
                
                // Destroy existing chart if exists
                if (window.bpjsChart) {
                    window.bpjsChart.destroy();
                    window.bpjsChart = null;
                }
                
                const ctx1 = document.getElementById('bpjsChart').getContext('2d');
                window.bpjsChart = new Chart(ctx1, {
                    type: 'bar',
                    data: { 
                        labels: labels, 
                        datasets: [{ 
                            label: 'Jumlah', 
                            data: values, 
                            backgroundColor:'#337ab7',
                            borderColor: '#337ab7',
                            borderWidth: 1
                        }] 
                    },
                    options: { 
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false }
                        },
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            }).fail(function(xhr, status, error) {
                console.error('BPJS Chart API Error:', error, xhr.responseText);
            });
            
            // Klaim Chart
            $.getJSON(`api_dashboard.php?action=status_klaim&${dateParams}`, function(data){
                console.log('Klaim Chart Data:', data);
                
                if (!data || data.length === 0) {
                    console.warn('No Klaim data found');
                    if (window.klaimChart) {
                        window.klaimChart.destroy();
                        window.klaimChart = null;
                    }
                    const ctx2Empty = document.getElementById('klaimChart').getContext('2d');
                    window.klaimChart = new Chart(ctx2Empty, {
                        type: 'bar',
                        data: { labels: ['No Data'], datasets: [
                            { label: 'Clear', data: [0], backgroundColor:'#ccc' },
                            { label: 'Pending', data: [0], backgroundColor:'#ccc' }
                        ] },
                        options: { 
                            responsive: true,
                            maintainAspectRatio: false
                        }
                    });
                    return;
                }
                
                const grouped = {};
                data.forEach(row=>{
                    if(!grouped[row.tgl_klaim]) grouped[row.tgl_klaim]={clear:0,pending:0};
                    grouped[row.tgl_klaim][row.status] = parseInt(row.jumlah);
                });
                const labels = Object.keys(grouped);
                const clear = labels.map(l=>grouped[l].clear||0);
                const pending = labels.map(l=>grouped[l].pending||0);
                
                // Destroy existing chart if exists
                if (window.klaimChart) {
                    window.klaimChart.destroy();
                    window.klaimChart = null;
                }
                
                const ctx2 = document.getElementById('klaimChart').getContext('2d');
                window.klaimChart = new Chart(ctx2, {
                    type: 'bar',
                    data: { 
                        labels: labels, 
                        datasets: [
                            { 
                                label: 'Clear', 
                                data: clear, 
                                backgroundColor:'#5cb85c',
                                borderColor: '#5cb85c',
                                borderWidth: 1
                            },
                            { 
                                label: 'Pending', 
                                data: pending, 
                                backgroundColor:'#d9534f',
                                borderColor: '#d9534f',
                                borderWidth: 1
                            }
                        ] 
                    },
                    options: { 
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            }).fail(function(xhr, status, error) {
                console.error('Klaim Chart API Error:', error, xhr.responseText);
            });
            
            // Kepuasan Chart
            $.getJSON(`api_dashboard.php?action=kepuasan_pasien&${dateParams}`, function(data){
                console.log('Kepuasan Chart Data:', data);
                
                if (!data || data.length === 0) {
                    console.warn('No Kepuasan data found');
                    if (window.kepuasanChart && typeof window.kepuasanChart.destroy === 'function') {
                        window.kepuasanChart.destroy();
                    }
                    window.kepuasanChart = new Chart(document.getElementById('kepuasanChart'), {
                        type: 'bar',
                        data: { labels: ['No Data'], datasets: [
                            { label: 'No Data', data: [0], backgroundColor:'#ccc' }
                        ] },
                        options: { responsive:true }
                    });
                    return;
                }
                
                const skorList = ['sangat_puas','puas','cukup','kurang','tidak_puas'];
                const grouped = {};
                data.forEach(row=>{
                    if(!grouped[row.tgl_survey]) grouped[row.tgl_survey]={};
                    grouped[row.tgl_survey][row.skor]=parseInt(row.jumlah);
                });
                const labels = Object.keys(grouped);
                const datasets = skorList.map((skor,i)=>({
                    label: skor.replace('_',' ').toUpperCase(),
                    data: labels.map(l=>grouped[l][skor]||0),
                    backgroundColor:['#337ab7','#5cb85c','#f0ad4e','#f7e25b','#d9534f'][i]
                }));
                
                // Destroy existing chart if exists
                if (window.kepuasanChart) {
                    window.kepuasanChart.destroy();
                    window.kepuasanChart = null;
                }
                
                const ctx3 = document.getElementById('kepuasanChart').getContext('2d');
                window.kepuasanChart = new Chart(ctx3, {
                    type: 'bar',
                    data: { labels: labels, datasets: datasets },
                    options: { 
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            }).fail(function(xhr, status, error) {
                console.error('Kepuasan Chart API Error:', error, xhr.responseText);
            });
        }
    });
    </script>
</body>

</html>
