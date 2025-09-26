<?php

require_once __DIR__ . '/../includes/encryption.php';
require_once __DIR__ . '/../config/config.php';

$secret_key = $config['encryption_key'] ?? 'your_secret_key_32_chars_long'; 
$method = 'aes-256-cbc';

$enc = isset($_GET['enc']) ? $_GET['enc'] : '';

// Decrypt if present, otherwise use mock data
$employee_data = [
    'id' => '',
    'name' => '',
    'position' => '',
    'department' => '',
    'baseSalary' => 0.00,
    'ot_normal_hours' => 0,
    'ot_special_hours' => 0,
    'ot_after_midnight_hours' => 0,
    'ot_holiday_hours' => 0,
    'total_normal_ot' => 0.00,
    'normal_amount_out' => 0.00,
    'after_night_ot' => 0.00,
    'ot_after_night' => 0.00,
    'holiday_normal_ot' => 0.00,
    'total_salary' => 0.00,
];

if ($enc) {
    try {
        // Decrypt the data
        $decrypted_json = decryptData($enc, $secret_key, $method);
        
        if ($decrypted_json) {
            $data = json_decode($decrypted_json, true);
            if ($data) {
                // Map to employee_data 
                $employee_data['id'] = $data['id'] ?? '';
                $employee_data['name'] = $data['name'] ?? '';
                $employee_data['position'] = $data['position'] ?? '';
                $employee_data['department'] = $data['department'] ?? '';
                $employee_data['baseSalary'] = $data['baseSalary'] ?? 0.00;
                $employee_data['ot_normal_hours'] = $data['ot_normal_hours'] ?? 0;
                $employee_data['ot_special_hours'] = $data['ot_special_hours'] ?? 0;
                $employee_data['ot_after_midnight_hours'] = $data['ot_after_midnight_hours'] ?? 0;
                $employee_data['ot_holiday_hours'] = $data['ot_holiday_hours'] ?? 0;
                $employee_data['total_normal_ot'] = $data['total_normal_ot'] ?? 0.00;
                $employee_data['normal_amount_out'] = $data['normal_amount_out'] ?? 0.00;
                $employee_data['after_night_ot'] = $data['after_night_ot'] ?? 0.00;
                $employee_data['ot_after_night'] = $data['ot_after_night'] ?? 0.00;
                $employee_data['holiday_normal_ot'] = $data['holiday_normal_ot'] ?? 0.00;
   
                $employee_data['total_salary'] = $employee_data['baseSalary'] + $employee_data['total_normal_ot'] + $employee_data['normal_amount_out'] + $employee_data['after_night_ot'] + $employee_data['ot_after_night'] + $employee_data['holiday_normal_ot'];
            }
        }
    } catch (Exception $e) {

        echo "<div class='alert alert-danger'>Error: " . htmlspecialchars($e->getMessage()) . "</div>";
        exit;
    }
} else {
    // Mock data if no enc param
    $mock_data = [
        'id' => 'EMP001',
        'name' => 'ចិត្រ្តា ប៉ាង',
        'position' => 'បុគ្គលិកអប់រំ',
        'department' => 'HR',
        'baseSalary' => 500.00,
        'ot_normal_hours' => 5,
        'ot_special_hours' => 3,
        'ot_after_midnight_hours' => 2,
        'ot_holiday_hours' => 1,
        'total_normal_ot' => 50.00,
        'normal_amount_out' => 30.00,
        'after_night_ot' => 20.00,
        'ot_after_night' => 15.00,
        'holiday_normal_ot' => 10.00,
        'total_salary' => 625.00,
    ];
    // Encrypt mock data for demonstration
    $encrypted_mock = encryptData(json_encode($mock_data), $secret_key, $method);
    // Simulate URL parameter for testing
    $_GET['enc'] = $encrypted_mock;
    $decrypted_json = decryptData($encrypted_mock, $secret_key, $method);
    if ($decrypted_json) {
        $data = json_decode($decrypted_json, true);
        if ($data) {
            $employee_data = $data;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salary Check</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Google Fonts: Noto Sans Khmer for Khmer script -->
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Khmer:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Custom CSS for improvements -->
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
    <div class="container my-5">
        <div class="card">
            <div class="header1">
                <h4>ព័ត៌មានប្រាក់បៀវត្ស</h4>
            </div>
            <div class="card-body">
                <!-- Employee Info -->
                <div class="employee-info row">
                    <div class="col-md-4">
                        <strong>ល.អ.ត ៖</strong> <?php echo htmlspecialchars($employee_data['id']); ?>
                    </div>
                    <div class="col-md-4">
                        <strong>ឈ្មោះ ៖</strong> <?php echo htmlspecialchars($employee_data['name']); ?>
                    </div>
                    <div class="col-md-4">
                        <strong>ប្រាក់បៀវត្ស ៖</strong> <?php echo number_format($employee_data['baseSalary'], 2); ?> $
                    </div>
                </div>

                <!-- OT Summary Table -->
                <div class="header">
                    <h4>ប្រាក់ខែគោល ៖ <?php echo number_format($employee_data['baseSalary'], 2); ?> $</h4>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped ot-table">
                        <tbody>
                            <tr>
                                <td>ថែមម៉ោង៖</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>ពេលថ្ងៃ៖</td>
                                <td><?php echo $employee_data['ot_normal_hours']; ?> ម៉ោង</td>
                                <td>Total Normal OT៖</td>
                                <td><?php echo number_format($employee_data['total_normal_ot'], 2); ?> $</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>ពេលយប់ ៖</td>
                                <td><?php echo $employee_data['ot_special_hours']; ?> ម៉ោង</td>
                                <td>Normal Amount out៖</td>
                                <td><?php echo number_format($employee_data['normal_amount_out'], 2); ?> $</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>ថ្ងៃយប់ ៖</td>
                                <td><?php echo $employee_data['ot_after_midnight_hours']; ?> ម៉ោង</td>
                                <td>After Night OT៖</td>
                                <td><?php echo number_format($employee_data['after_night_ot'], 2); ?> $</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>OT After Night៖</td>
                                <td><?php echo number_format($employee_data['ot_after_night'], 2); ?> $</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td colspan="2"></td>
                                <td>Holiday Normal OT៖</td>
                                <td><?php echo number_format($employee_data['holiday_normal_ot'], 2); ?> $</td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="header">
                        <h4>ប្រាក់ខែគោល ៖ <?php echo number_format($employee_data['baseSalary'], 2); ?> $</h4>
                    </div>
                    <table class="table table-striped ot-table">
                        <tbody>
                            <tr>
                                <td>ថែមម៉ោង៖</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>ពេលថ្ងៃ ៖</td>
                                <td><?php echo $employee_data['ot_normal_hours']; ?> ម៉ោង</td>
                                <td>Total Normal OT៖</td>
                                <td><?php echo number_format($employee_data['total_normal_ot'], 2); ?> $</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>ពេលយប់ ៖</td>
                                <td><?php echo $employee_data['ot_special_hours']; ?> ម៉ោង</td>
                                <td>Normal Amount out៖</td>
                                <td><?php echo number_format($employee_data['normal_amount_out'], 2); ?> $</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>ថ្ងៃយប់ ៖</td>
                                <td><?php echo $employee_data['ot_after_midnight_hours']; ?> ម៉ោង</td>
                                <td>After Night OT៖</td>
                                <td><?php echo number_format($employee_data['after_night_ot'], 2); ?> $</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>OT After Night៖</td>
                                <td><?php echo number_format($employee_data['ot_after_night'], 2); ?> $</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td colspan="2"></td>
                                <td>Holiday Normal OT៖</td>
                                <td><?php echo number_format($employee_data['holiday_normal_ot'], 2); ?> $</td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="header">
                        <h4>ប្រាក់ខែសរុប ៖ <?php echo number_format($employee_data['total_salary'], 2); ?> $</h4>
                    </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS  -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <!-- Custom JS if needed  -->
    <script>
        
        console.log('Page loaded');
    </script>
</body>
</html>

<?php
// config/config.php

return [
    'encryption_key' => 'your_secret_key_32_chars_long', // Replace with secure key
    'base_url' => 'http://localhost/SalaryCheck/public/index.php?enc=Z+CYEzPZ7PUWNd8y7dQGRStmTFhXNmFYOU4wRmVva085dzlLRkJmdTlIV0NJMkY0VTcxRWdqSVJBNkExQ1pQc2E3dGYxZVJXckxoc2tSMzRjUVNkLyt0Mkpma3psaFJLN0pGMElBPT0=',
];
?>

<?php

function encryptData($data, $key, $method = 'aes-256-cbc') {
    // Generate random IV 
    $iv = random_bytes(16); 
    
    // Encrypt the data
    $encrypted = openssl_encrypt($data, $method, $key, 0, $iv);
    if ($encrypted === false) {
        throw new Exception('Encryption failed');
    }
    
    // Combine IV + encrypted data, base64-encode for URL safety
    return base64_encode($iv . $encrypted);
}


function decryptData($encrypted_data, $key, $method = 'aes-256-cbc') {
    
    $enc_data = base64_decode($encrypted_data);
    if ($enc_data === false) {
        throw new Exception('Invalid encoding');
    }
    
    
    $iv = substr($enc_data, 0, 16);
    $ciphertext = substr($enc_data, 16);
    
    // Decrypt
    $decrypted = openssl_decrypt($ciphertext, $method, $key, 0, $iv);
    if ($decrypted === false) {
        throw new Exception('Decryption failed');
    }
    
    return $decrypted;
}


require_once __DIR__ . '/../includes/encryption.php';
require_once __DIR__ . '/../config/config.php';
$config = require __DIR__ . '/../config/config.php'; 
$secret_key = $config['encryption_key'];
$method = 'aes-256-cbc';

$test_data = ['id' => 'TEST002', 'name' => 'Test User', 'baseSalary' => 600.00]; 
$json = json_encode($test_data);
$enc = encryptData($json, $secret_key, $method);
$url = 'http://localhost/SalaryCheck/public/index.php?enc=' . $enc;
echo "Test URL: " . $url . "\n";




?>



<?php
// Sample employee data (replace with actual data source)
$employee_data = [
    'Emp_ID' => '12345',
    'KhmerName' => 'សុខ ស៊ីណា',
    'SalaryDTKH' => 'ខែ មករា ២០២៥',
    'Basic' => 300,
    'Total_Normal_OT' => 10,
    'Normal_Amount' => 50,
    'Aft_Night_OT' => 5,
    'OT_Aft_Night' => 30,
    'Holiday_Normal_OT' => 8,
    'Total_HOT' => 48,
    'Night_Wage' => 20,
    'Alw_Att' => 10,
    'Alw_Housing' => 50,
    'Alw_GSTARS' => 15,
    'Alw_License' => 25,
    'Alw_Position' => 30,
    'Alw_Additional' => 10,
    'Seniority' => 20,
    'SaleAL' => 15,
    'Adjust' => 5,
    'Total_1' => 568,
    'Abs_(Day)' => 1,
    'Abs_(Hour)' => 8,
    'Abs_(Unpaid)' => 4,
    'Abs_Amount' => 20,
    'Alw_KHNY' => 100,
    'Advance' => 200,
    'Deduct' => 10,
    'Pension' => 15,
    'Total_2' => 423
];

// Handle PDF download
if (isset($_GET['action']) && $_GET['action'] === 'download_pdf') {
    require_once 'vendor/autoload.php'; // Adjust path to TCPDF if needed
    $pdf = new TCPDF();
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Salary System');
    $pdf->SetTitle('Salary Slip');
    $pdf->SetFont('freeserif', '', 12); // Support Khmer font
    $pdf->AddPage();

    // Capture the HTML content for PDF
    ob_start();
    ?>
    <div style="font-family: 'Noto Sans Khmer', sans-serif;">
        <h2>ព័ត៌មានប្រាក់បៀវត្សន៏</h2>
        <p><strong>ល.អ.ត ៖</strong> <?php echo htmlspecialchars($employee_data['Emp_ID']); ?></p>
        <p><strong>ឈ្មោះ ៖</strong> <?php echo htmlspecialchars($employee_data['KhmerName']); ?></p>
        <p><strong>បៀវត្សន៏ខែ ៖</strong> <?php echo htmlspecialchars($employee_data['SalaryDTKH']); ?></p>
        <h3>ប្រាក់ខែគោល ៖ <?php echo number_format($employee_data['Basic']); ?> $</h3>
        <table border="1" cellpadding="5">
            <tr><td>ថែមម៉ោង៖</td><td></td></tr>
            <tr><td>ពេលថ្ងៃ៖</td><td><?php echo number_format($employee_data['Total_Normal_OT']); ?> ម៉ោង</td><td>ចំនួនទឹកប្រាក់៖</td><td><?php echo number_format($employee_data['Normal_Amount']); ?> $</td></tr>
            <tr><td>ពេលយប់៖</td><td><?php echo number_format($employee_data['Aft_Night_OT']); ?> ម៉ោង</td><td>ចំនួនទឹកប្រាក់៖</td><td><?php echo number_format($employee_data['OT_Aft_Night']); ?> $</td></tr>
            <tr><td>ថ្ងៃឈប់៖</td><td><?php echo number_format($employee_data['Holiday_Normal_OT']); ?> ម៉ោង</td><td>ចំនួនទឹកប្រាក់៖</td><td><?php echo number_format($employee_data['Total_HOT']); ?> $</td></tr>
            <tr><td>ប្រាក់បន្ថែម វេនយប់៖</td><td><?php echo number_format($employee_data['Night_Wage']); ?> $</td></tr>
            <tr><td>ប្រាក់ឧបត្ថម្ភ វត្តមាន៖</td><td><?php echo number_format($employee_data['Alw_Att']); ?> $</td></tr>
            <tr><td>ប្រាក់ឧបត្ថម្ភ ការស្នាក់នៅ៖</td><td><?php echo number_format($employee_data['Alw_Housing']); ?> $</td></tr>
            <tr><td>ប្រាក់បន្ថែម ជីស្ដា(G-STARS)៖</td><td><?php echo number_format($employee_data['Alw_GSTARS']); ?> $</td></tr>
            <tr><td>ប្រាក់បន្ថែម License៖</td><td><?php echo number_format($employee_data['Alw_License']); ?> $</td></tr>
            <tr><td>ប្រាក់បន្ថែម មុខតំណែង៖</td><td><?php echo number_format($employee_data['Alw_Position']); ?> $</td></tr>
            <tr><td>ប្រាក់បន្ថែម ផ្សេងៗ៖</td><td><?php echo number_format($employee_data['Alw_Additional']); ?> $</td></tr>
            <tr><td>ប្រាក់អតីតភាពការងារ៖</td><td><?php echo number_format($employee_data['Seniority']); ?> $</td></tr>
            <tr><td>ប្រាក់លក់ថ្ងៃឈប់ប្រចាំឆ្នាំ៖</td><td><?php echo number_format($employee_data['SaleAL']); ?> $</td></tr>
            <tr><td>កែសម្រួល៖</td><td><?php echo number_format($employee_data['Adjust']); ?> $</td></tr>
        </table>
        <h3>ប្រាក់បៀវត្សន៏សរុប៖ <?php echo number_format($employee_data['Total_1']); ?> $</h3>
        <table border="1" cellpadding="5">
            <tr><td>អវត្តមាន៖</td><td></td></tr>
            <tr><td>មាន ច្បាប់៖</td><td><?php echo number_format($employee_data['Abs_(Day)']); ?> ថ្ងៃ <?php echo number_format($employee_data['Abs_(Hour)']); ?> ម៉ោង</td></tr>
            <tr><td>គ្មានច្បាប់៖</td><td><?php echo number_format($employee_data['Abs_(Unpaid)']); ?> ម៉ោង</td><td>ចំនួនទឹកប្រាក់៖</td><td><?php echo number_format($employee_data['Abs_Amount']); ?> $</td></tr>
            <tr><td>ប្រាក់ឧបត្ថម្ភចូលឆ្នាំខ្មែរ៖</td><td><?php echo number_format($employee_data['Alw_KHNY']); ?> $</td></tr>
            <tr><td>ប្រាក់បៀវត្សន៏ លើកទី ១៖</td><td><?php echo number_format($employee_data['Advance']); ?> $</td></tr>
            <tr><td>ការផាកពិន័យផ្សេងៗ៖</td><td><?php echo number_format($employee_data['Deduct']); ?> $</td></tr>
            <tr><td>ភាគទានសោធន៖</td><td><?php echo number_format($employee_data['Pension']); ?> $</td></tr>
        </table>
        <h3>ប្រាក់បៀវត្សន៏ លើកទី ២៖ <?php echo number_format($employee_data['Total_2']); ?> $</h3>
    </div>
    <?php
    $html = ob_get_clean();
    $pdf->writeHTML($html, true, false, true, false, '');
    $pdf->Output('salary_slip.pdf', 'D');
    exit;
}
?>

<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salary Check</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Google Fonts: Noto Sans Khmer for Khmer script -->
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Khmer:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Khmer&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="./css/style.css">
    <!-- html2canvas for image download -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
</head>
<body>
    <div class="container my-5">
        <div class="card" id="salaryCard">
            <div class="header1">
                <h4>ព័ត៌មានប្រាក់បៀវត្សន៏</h4>
            </div>
            <div class="card-body">
                <!-- Employee Info -->
                <div class="employee-info row">
                    <div class="col-md-4">
                        <strong>ល.អ.ត ៖</strong> <?php echo htmlspecialchars($employee_data['Emp_ID']); ?>
                    </div>
                    <div class="col-md-4">
                        <strong>ឈ្មោះ ៖</strong> <?php echo htmlspecialchars($employee_data['KhmerName']); ?>
                    </div>
                    <div class="col-md-4">
                        <strong>បៀវត្សន៏ខែ ៖</strong> <?php echo htmlspecialchars($employee_data['SalaryDTKH']); ?> 
                    </div>
                </div>

                <!-- OT Summary Table -->
                <div class="header">
                    <h4>ប្រាក់ខែគោល ៖ <?php echo number_format($employee_data['Basic']); ?> $</h4>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped ot-table">
                        <tbody>
                            <tr>
                                <td></td>
                                <td>ថែមម៉ោង៖</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td class="disable"></td>
                                <td>ពេលថ្ងៃ៖</td>
                                <td><?php echo number_format($employee_data['Total_Normal_OT']); ?>&nbsp;ម៉ោង</td>
                                <td></td>
                                <td>ចំនួនទឹកប្រាក់៖</td>
                                <td><?php echo number_format($employee_data['Normal_Amount']); ?>&nbsp;$</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>ពេលយប់៖</td>
                                <td><?php echo number_format($employee_data['Aft_Night_OT']); ?>&nbsp;ម៉ោង</td>
                                <td></td>
                                <td>ចំនួនទឹកប្រាក់៖</td>
                                <td><?php echo number_format($employee_data['OT_Aft_Night']); ?>&nbsp;$</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>ថ្ងៃឈប់៖</td>
                                <td><?php echo number_format($employee_data['Holiday_Normal_OT']); ?>&nbsp;ម៉ោង</td>
                                <td></td>
                                <td>ចំនួនទឹកប្រាក់៖</td>
                                <td><?php echo number_format($employee_data['Total_HOT']); ?>&nbsp;$</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>ប្រាក់បន្ថែម&nbsp;វេនយប់៖</td>
                                <td><?php echo number_format($employee_data['Night_Wage']); ?>&nbsp;$</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>ប្រាក់ឧបត្ថម្ភ&nbsp;វត្តមាន៖</td>
                                <td><?php echo number_format($employee_data['Alw_Att']); ?>&nbsp;$</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>ប្រាក់ឧបត្ថម្ភ&nbsp;ការស្នាក់នៅ៖</td>
                                <td><?php echo number_format($employee_data['Alw_Housing']); ?>&nbsp;$</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>ប្រាក់បន្ថែម ជីស្ដា(G-STARS)៖</td>
                                <td><?php echo number_format($employee_data['Alw_GSTARS']); ?>&nbsp;$</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>ប្រាក់បន្ថែម License៖</td>
                                <td><?php echo number_format($employee_data['Alw_License']); ?>&nbsp;$</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>ប្រាក់បន្ថែម&nbsp;មុខតំណែង៖</td>
                                <td><?php echo number_format($employee_data['Alw_Position']); ?>&nbsp;$</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>ប្រាក់បន្ថែម ផ្សេងៗ៖</td>
                                <td><?php echo number_format($employee_data['Alw_Additional']); ?>&nbsp;$</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>ប្រាក់អតីតភាពការងារ៖</td>
                                <td><?php echo number_format($employee_data['Seniority']); ?>&nbsp;$</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>ប្រាក់លក់ថ្ងៃឈប់ប្រចាំឆ្នាំ៖</td>
                                <td><?php echo number_format($employee_data['SaleAL']); ?>&nbsp;$</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>កែសម្រួល៖</td>
                                <td><?php echo number_format($employee_data['Adjust']); ?>&nbsp;$</td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="header">
                        <h4>ប្រាក់បៀវត្សន៏សរុប៖ <?php echo number_format($employee_data['Total_1']); ?>&nbsp;$</h4>
                    </div>
                    <table class="table table-striped ot-table">
                        <tbody>
                            <tr>
                                <td></td>
                                <td>អវត្តមាន៖</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>មាន&nbsp;ច្បាប់៖&nbsp;<?php echo number_format($employee_data['Abs_(Day)']); ?>&nbsp;ថ្ងៃ&nbsp;<?php echo number_format($employee_data['Abs_(Hour)']); ?>&nbsp;ម៉ោង</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>គ្មានច្បាប់៖&nbsp;<?php echo number_format($employee_data['Abs_(Unpaid)']); ?>&nbsp;ម៉ោង</td>
                                <td></td>
                                <td></td>
                                <td><span>ចំនួនទឹកប្រាក់៖</span></td>
                                <td><?php echo number_format($employee_data['Abs_Amount']); ?>&nbsp;$</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td><span class="fon">ប្រាក់ឧបត្ថម្ភចូលឆ្នាំខ្មែរ&nbsp;៖</span></td>
                                <td><?php echo number_format($employee_data['Alw_KHNY']); ?>&nbsp;$</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td><span class="fon">ប្រាក់បៀវត្សន៏&nbsp;លើកទី&nbsp;១&nbsp;៖</span></td>
                                <td><?php echo number_format($employee_data['Advance']); ?>&nbsp;$</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td><span class="fon">ការផាកពិន័យផ្សេងៗ&nbsp;៖</span></td>
                                <td><?php echo number_format($employee_data['Deduct']); ?>&nbsp;$</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td><span class="fon">ភាគទានសោធន&nbsp;៖</span></td>
                                <td><?php echo number_format($employee_data['Pension']); ?>&nbsp;$</td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="header">
                        <h4>ប្រាក់បៀវត្សន៏&nbsp;លើកទី&nbsp;២៖ <?php echo number_format($employee_data['Total_2']); ?>&nbsp;$</h4>
                    </div>
                </div>
            </div>
        </div>
        <!-- Download Buttons -->
        <div class="mt-3 text-end">
            <a href="?action=download_pdf" class="btn btn-primary me-2">ទាញយកជា PDF</a>
            <button onclick="downloadImage()" class="btn btn-primary">ទាញយកជារូបភាព</button>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <!-- JavaScript for Image Download -->
    <script>
        function downloadImage() {
            html2canvas(document.getElementById('salaryCard'), {
                scale: 2, 
                useCORS: true 
            }).then(canvas => {
                const link = document.createElement('a');
                link.download = 'salary_slip.png';
                link.href = canvas.toDataURL('image/png');
                link.click();
            });
        }
    </script>
</body>
</html>

<?php
// includes/pdf_generator.php

require_once __DIR__ . '/../vendor/autoload.php'; // Load Composer autoloader (mPDF)

use Mpdf\Mpdf;

/**
 * Generates a salary slip PDF using mPDF.
 *
 * @param array $employee_data The data array with keys like 'Emp_ID', 'KhmerName', etc.
 */
function generateSalaryPDF($employee_data) {
    // Define custom temp directory
    $tempDir = __DIR__ . '/../tmp';

    // Ensure temp directory exists and is writable
    try {
        if (!is_dir($tempDir)) {
            if (!mkdir($tempDir, 0755, true)) {
                throw new Exception("Failed to create temporary directory: $tempDir");
            }
        }
        if (!is_writable($tempDir)) {
            if (!chmod($tempDir, 0755)) {
                throw new Exception("Temporary directory $tempDir is not writable");
            }
        }
    } catch (Exception $e) {
        error_log("PDF Temp Dir Error: " . $e->getMessage());
        http_response_code(500);
        echo '<div class="alert alert-danger">Error: ' . htmlspecialchars($e->getMessage()) . '</div>';
        exit;
    }

    // mPDF configuration with custom temp directory and Khmer support
    $mpdfConfig = [
        'mode' => 'utf-8',
        'format' => 'A4',
        'orientation' => 'P', // Portrait
        'tempDir' => $tempDir, // Custom writable temp directory
        'fontDir' => [__DIR__ . '/../fonts'], // Optional: Custom font directory
        'fontdata' => [
            'khmer' => [
                'R' => 'khmerosmoul', // Built-in mPDF Khmer font
                'useOTL' => 0xFF, // Enable OpenType Layout for Khmer
                'useKashida' => 75,
            ],
            'notokhmer' => [
                'R' => 'NotoSansKhmer-Regular.ttf', // Place in /fonts if using
                'B' => 'NotoSansKhmer-Bold.ttf',
                'useOTL' => 0xFF,
                'useKashida' => 75,
            ],
        ],
        'default_font' => 'khmer', // Use built-in Khmer font
        'default_font_size' => 10,
        'margin_left' => 15,
        'margin_right' => 15,
        'margin_top' => 16,
        'margin_bottom' => 16,
        'margin_header' => 9,
        'margin_footer' => 9,
    ];

    try {
        // Initialize mPDF
        $mpdf = new Mpdf($mpdfConfig);

        // CSS for styling (Bootstrap-inspired, minimal for PDF)
        $css = '
        @import url(https://fonts.googleapis.com/css2?family=Noto+Sans+Khmer:wght@300;400;500;600;700&display=swap);
        @import url(https://fonts.googleapis.com/css2?family=Khmer&display=swap);
        body { font-family: "khmer", "Noto Sans Khmer", sans-serif; font-size: 10pt; color: #000; }
        .container { max-width: 800px; margin: 0 auto; padding: 10px; }
        .card { border: 1px solid #000; padding: 10px; }
        .header1 { text-align: center; font-size: 14pt; font-weight: bold; margin-bottom: 10px; }
        .header { text-align: center; font-size: 12pt; font-weight: bold; margin: 10px 0; }
        .employee-info { margin-bottom: 10px; }
        .employee-info div { margin-bottom: 5px; }
        .table { width: 100%; border-collapse: collapse; margin: 5px 0; }
        .table td { padding: 5px; text-align: left; vertical-align: top; border: 1px solid #000; }
        .table-striped tbody tr:nth-of-type(odd) { background-color: #f9f9f9; }
        .disable { border: none; }
        .fon { font-weight: bold; }
        ';

        // HTML content (matches index.php structure)
        $html = '
        <div class="container">
            <div class="card">
                <div class="header1">
                    <h4>ព័ត៌មានប្រាក់បៀវត្សន៏</h4>
                </div>
                <div class="card-body">
                    <!-- Employee Info -->
                    <div class="employee-info">
                        <div><strong>ល.អ.ត ៖</strong> ' . htmlspecialchars($employee_data['Emp_ID']) . '</div>
                        <div><strong>ឈ្មោះ ៖</strong> ' . htmlspecialchars($employee_data['KhmerName']) . '</div>
                        <div><strong>បៀវត្សន៏ខែ ៖</strong> ' . htmlspecialchars($employee_data['SalaryDTKH']) . '</div>
                    </div>
                    <!-- Basic Salary -->
                    <div class="header">
                        <h4>ប្រាក់ខែគោល ៖ ' . number_format($employee_data['Basic']) . ' $</h4>
                    </div>
                    <!-- OT Table -->
                    <div class="table-responsive">
                        <table class="table table-striped ot-table">
                            <tbody>
                                <tr><td></td><td>ថែមម៉ោង៖</td><td></td><td></td><td></td><td></td></tr>
                                <tr><td class="disable"></td><td>ពេលថ្ងៃ៖</td><td>' . number_format($employee_data['Total_Normal_OT']) . '&nbsp;ម៉ោង</td><td></td><td>ចំនួនទឹកប្រាក់៖</td><td>' . number_format($employee_data['Normal_Amount']) . '&nbsp;$</td></tr>
                                <tr><td></td><td>ពេលយប់៖</td><td>' . number_format($employee_data['Aft_Night_OT']) . '&nbsp;ម៉ោង</td><td></td><td>ចំនួនទឹកប្រាក់៖</td><td>' . number_format($employee_data['OT_Aft_Night']) . '&nbsp;$</td></tr>
                                <tr><td></td><td>ថ្ងៃឈប់៖</td><td>' . number_format($employee_data['Holiday_Normal_OT']) . '&nbsp;ម៉ោង</td><td></td><td>ចំនួនទឹកប្រាក់៖</td><td>' . number_format($employee_data['Total_HOT']) . '&nbsp;$</td></tr>
                                <tr><td></td><td></td><td></td><td></td><td>ប្រាក់បន្ថែម&nbsp;វេនយប់៖</td><td>' . number_format($employee_data['Night_Wage']) . '&nbsp;$</td></tr>
                                <tr><td></td><td></td><td></td><td></td><td>ប្រាក់ឧបត្ថម្ភ&nbsp;វត្តមាន៖</td><td>' . number_format($employee_data['Alw_Att']) . '&nbsp;$</td></tr>
                                <tr><td></td><td></td><td></td><td></td><td>ប្រាក់ឧបត្ថម្ភ&nbsp;ការស្នាក់នៅ៖</td><td>' . number_format($employee_data['Alw_Housing']) . '&nbsp;$</td></tr>
                                <tr><td></td><td></td><td></td><td></td><td>ប្រាក់បន្ថែម ជីស្ដា(G-STARS)៖</td><td>' . number_format($employee_data['Alw_GSTARS']) . '&nbsp;$</td></tr>
                                <tr><td></td><td></td><td></td><td></td><td>ប្រាក់បន្ថែម License៖</td><td>' . number_format($employee_data['Alw_License']) . '&nbsp;$</td></tr>
                                <tr><td></td><td></td><td></td><td></td><td>ប្រាក់បន្ថែម&nbsp;មុខតំណែង៖</td><td>' . number_format($employee_data['Alw_Position']) . '&nbsp;$</td></tr>
                                <tr><td></td><td></td><td></td><td></td><td>ប្រាក់បន្ថែម ផ្សេងៗ៖</td><td>' . number_format($employee_data['Alw_Additional']) . '&nbsp;$</td></tr>
                                <tr><td></td><td></td><td></td><td></td><td>ប្រាក់អតីតភាពការងារ៖</td><td>' . number_format($employee_data['Seniority']) . '&nbsp;$</td></tr>
                                <tr><td></td><td></td><td></td><td></td><td>ប្រាក់លក់ថ្ងៃឈប់ប្រចាំឆ្នាំ៖</td><td>' . number_format($employee_data['SaleAL']) . '&nbsp;$</td></tr>
                                <tr><td></td><td></td><td></td><td></td><td>កែសម្រួល៖</td><td>' . number_format($employee_data['Adjust']) . '&nbsp;$</td></tr>
                            </tbody>
                        </table>
                        <div class="header">
                            <h4>ប្រាក់បៀវត្សន៏សរុប៖ ' . number_format($employee_data['Total_1']) . '&nbsp;$</h4>
                        </div>
                        <table class="table table-striped ot-table">
                            <tbody>
                                <tr><td></td><td>អវត្តមាន៖</td><td></td><td></td><td></td><td></td></tr>
                                <tr><td></td><td>មាន&nbsp;ច្បាប់៖&nbsp;' . number_format($employee_data['Abs_(Day)']) . '&nbsp;ថ្ងៃ&nbsp;' . number_format($employee_data['Abs_(Hour)']) . '&nbsp;ម៉ោង</td><td></td><td></td><td></td><td></td></tr>
                                <tr><td></td><td>គ្មានច្បាប់៖&nbsp;' . number_format($employee_data['Abs_(Unpaid)']) . '&nbsp;ម៉ោង</td><td></td><td></td><td><span class="fon">ចំនួនទឹកប្រាក់៖</span></td><td>' . number_format($employee_data['Abs_Amount']) . '&nbsp;$</td></tr>
                                <tr><td></td><td></td><td></td><td></td><td><span class="fon">ប្រាក់ឧបត្ថម្ភចូលឆ្នាំខ្មែរ&nbsp;៖</span></td><td>' . number_format($employee_data['Alw_KHNY']) . '&nbsp;$</td></tr>
                                <tr><td></td><td></td><td></td><td></td><td><span class="fon">ប្រាក់បៀវត្សន៏&nbsp;លើកទី&nbsp;១&nbsp;៖</span></td><td>' . number_format($employee_data['Advance']) . '&nbsp;$</td></tr>
                                <tr><td></td><td></td><td></td><td></td><td><span class="fon">ការផាកពិន័យផ្សេងៗ&nbsp;៖</span></td><td>' . number_format($employee_data['Deduct']) . '&nbsp;$</td></tr>
                                <tr><td></td><td></td><td></td><td></td><td><span class="fon">ភាគទានសោធន&nbsp;៖</span></td><td>' . number_format($employee_data['Pension']) . '&nbsp;$</td></tr>
                            </tbody>
                        </table>
                        <div class="header">
                            <h4>ប្រាក់បៀវត្សន៏&nbsp;លើកទី&nbsp;២៖ ' . number_format($employee_data['Total_2']) . '&nbsp;$</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>';

        // Write CSS and HTML to mPDF
        $mpdf->WriteHTML($css, \Mpdf\HTMLParserMode::HEADER_CSS);
        $mpdf->WriteHTML($html, \Mpdf\HTMLParserMode::HTML_BODY);

        // Generate filename with Emp_ID
        $filename = 'salary_slip_' . ($employee_data['Emp_ID'] ?? 'unknown') . '.pdf';

        // Output as download
        $mpdf->Output($filename, 'D'); // 'D' = Download

    } catch (\Mpdf\MpdfException $e) {
        // Handle mPDF errors
        error_log('mPDF Error: ' . $e->getMessage());
        http_response_code(500);
        echo '<div class="alert alert-danger">PDF Generation Error: ' . htmlspecialchars($e->getMessage()) . '</div>';
        exit;
    }
}
?>



<?php 
   
    require_once __DIR__ . '/../vendor/autoload.php';
    require_once __DIR__ . '/../includes/encryption.php';
    require_once __DIR__ . '/../config/config.php';

    $secret_key = $config['encryption_key'] ?? 'your_secret_key_32_chars_long'; 
    $method = 'aes-256-cbc';

   $enc = isset($_GET['enc']) ? $_GET['enc'] : '';


   $employee_data = [
    'Emp_ID' => '',
    'KhmerName' => '',
    'SalaryDTKH' => '',
    'Basic' => 56,
    'Total_Normal_OT' => 56,
    'Normal_Amount' => 56,
    'Aft_Night_OT' => 56,
    'OT_Aft_Night' => 56,
    'Holiday_Normal_OT' => 56,
    'Total_HOT' => 56,
    'Night_Wage' => 56,
    'Alw_Att' => 56,
    'Alw_Housing' => 56,
    'Alw_GSTARS' => 56,
    'Alw_License' => 56,
    'Alw_Position' => 56,
    'Alw_Additional' => 56,
    'Seniority' => 56,
    'Alw_KHNY' => 56,
    'SaleAL' => 56,
    'Adjust' => 56,
    'Total_1' => 56,
    'Abs_(Day)' => 56,
    'Abs_(Hour)' => 56,
    'Abs_(Unpaid)' => 56,
    'Abs_Amount' => 56,
    'Advance' => 56,
    'Deduct' => 56,
    'Pension' => 56,
    'Total_2' => 56,
   ];

   if ($enc) {
        try {

            $decrypted_json = decryptData($enc, $secret_key, $method);
            
            if ($decrypted_json) {
                $data = json_decode($decrypted_json, true);

                if ($data) {
                    // Map to Employees data
                    $employee_data['Emp_ID']  = $data['e'] ?? '';
                    $employee_data['KhmerName'] = $data['k'] ?? '';
                    $employee_data['SalaryDTKH'] = $data['s'] ?? '';
                    $employee_data['Basic'] = $data['b'] ?? 56;
                    $employee_data['Total_Normal_OT'] = $data['th'] ?? 56;
                    $employee_data['Normal_Amount'] = $data['na'] ?? 56;
                    $employee_data['Aft_Night_OT'] = $data['an'] ?? 56;
                    $employee_data['OT_Aft_Night'] = $data['oa'] ?? 56;
                    $employee_data['Holiday_Normal_OT'] = $data['hn'] ?? 56;
                    $employee_data['Total_HOT'] = $data['th'] ?? 56;
                    $employee_data['Night_Wage'] = $data['nw'] ?? 56;
                    $employee_data['Alw_Att'] = $data['aa'] ?? 56;
                    $employee_data['Alw_Housing'] = $data['ah'] ?? 56;
                    $employee_data['Alw_GSTARS'] = $data['ag'] ?? 56;
                    $employee_data['Alw_License'] = $data['al'] ?? 56;
                    $employee_data['Alw_Position'] = $data['ap'] ?? 56;
                    $employee_data['Alw_Additional'] = $data['a1'] ?? 56;
                    $employee_data['Seniority'] = $data['sn'] ?? 56;
                    $employee_data['Alw_KHNY'] = $data['ak'] ?? 56;
                    $employee_data['SaleAL'] = $data['sa'] ?? 56;
                    $employee_data['Adjust'] = $data['aj'] ?? 56;
                    $employee_data['Total_1'] = $data['t1'] ?? 56;
                    $employee_data['Abs_(Day)'] = $data['a2'] ?? 56;
                    $employee_data['Abs_(Hour)'] = $data['a3'] ?? 56;
                    $employee_data['Abs_(Unpaid'] = $data['au'] ?? 56;
                    $employee_data['Abs_Amount'] = $data['am'] ?? 56;
                    $employee_data['Advance'] = $data['av'] ?? 56;
                    $employee_data['Deduct'] = $data['dd'] ?? 56;
                    $employee_data['Pension'] = $data['pn'] ?? 56;
                    $employee_data['Total_2'] = $data['t2'] ?? 56;
                }
            }
        } catch (Exception $e) {
            echo "<div class='alert alert-danger'>Error: " . htmlspecialchars($e->getMessage()) . "</div>";
            exit; 
        } 
    }  
    else {
        // Else mock Data
        $mock_data = [
            'e' => '9122-33889',
            'k' => 'បូរា​ សុជាតា',
            's' => 'ប្រាំរយដុល្លា',
            'b' => 1000.00,
            'th' => 5,
            'na' => 10,
            'an' => 20,
            'oa' => 30,
            'hn' => 35,
            'th' => 230,
            'nw' => 12,
            'aa' => 31,
            'ah' => 25,
            'ag' => 34,
            'al' => 56,
            'ap' => 23,
            'a1' => 42,
            'sn' => 15,
            'ak' => 45,
            'sa' => 73,
            'aj' => 23,
            't1' => 560.00,
            'a2' => 13,
            'a3' => 16,
            'au' => 16,
            'am' => 34,
            'av' => 16,
            'dd' => 35,
            'pn' => 25,
            't2' => 780.00,
        ];
        
        $encrypted_mock = encryptData(json_encode($mock_data), $secret_key, $method);
        $_GET['enc'] = $encrypted_mock;
        $decrypted_json = decryptData($encrypted_mock, $secret_key, $method);
        if ($decrypted_json) {
            $data = json_decode($decrypted_json, true);
            if($data) {
                $employee_data = $data;
            }
        }
    }

?>




<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salary Check</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Google Fonts: Noto Sans Khmer for Khmer script -->
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Khmer:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Khmer&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="./css/style.css">
    <!-- html2canvas for image download -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
</head>
<body>
    <div class="container my-5">
        <div class="card" id="salaryCard">
            <div class="header1">
                <h4>ព័ត៌មានប្រាក់បៀវត្សន៏</h4>
            </div>
            <div class="card-body">
                <!-- Employee Info -->
                <div class="employee-info row">
                    <div class="col-md-4">
                        <strong>ល.អ.ត ៖</strong> <?php echo htmlspecialchars($employee_data['e']); ?>
                    </div>
                    <div class="col-md-4">
                        <strong>ឈ្មោះ ៖</strong> <?php echo htmlspecialchars($employee_data['k']); ?>
                    </div>
                    <div class="col-md-4">
                        <strong>បៀវត្សន៏ខែ ៖</strong> <?php echo htmlspecialchars($employee_data['s']); ?> 
                    </div>
                </div>

                <!-- OT Summary Table -->
                <div class="header">
                    <h4>ប្រាក់ខែគោល ៖ <?php echo number_format($employee_data['b']); ?> $</h4>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped ot-table">
                        <tbody>
                            <tr>
                                <td></td>
                                <td>ថែមម៉ោង៖</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td class="disable"></td>
                                <td>ពេលថ្ងៃ៖</td>
                                <td><?php echo number_format($employee_data['th']); ?>&nbsp;ម៉ោង</td>
                                <td></td>
                                <td>ចំនួនទឹកប្រាក់៖</td>
                                <td><?php echo number_format($employee_data['na']); ?>&nbsp;$</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>ពេលយប់៖</td>
                                <td><?php echo number_format($employee_data['an']); ?>&nbsp;ម៉ោង</td>
                                <td></td>
                                <td>ចំនួនទឹកប្រាក់៖</td>
                                <td><?php echo number_format($employee_data['oa']); ?>&nbsp;$</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>ថ្ងៃឈប់៖</td>
                                <td><?php echo number_format($employee_data['hn']); ?>&nbsp;ម៉ោង</td>
                                <td></td>
                                <td>ចំនួនទឹកប្រាក់៖</td>
                                <td><?php echo number_format($employee_data['th']); ?>&nbsp;$</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>ប្រាក់បន្ថែម&nbsp;វេនយប់៖</td>
                                <td><?php echo number_format($employee_data['nw']); ?>&nbsp;$</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>ប្រាក់ឧបត្ថម្ភ&nbsp;វត្តមាន៖</td>
                                <td><?php echo number_format($employee_data['aa']); ?>&nbsp;$</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>ប្រាក់ឧបត្ថម្ភ&nbsp;ការស្នាក់នៅ៖</td>
                                <td><?php echo number_format($employee_data['ah']); ?>&nbsp;$</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>ប្រាក់បន្ថែម ជីស្ដា(G-STARS)៖</td>
                                <td><?php echo number_format($employee_data['ag']); ?>&nbsp;$</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>ប្រាក់បន្ថែម License៖</td>
                                <td><?php echo number_format($employee_data['al']); ?>&nbsp;$</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>ប្រាក់បន្ថែម&nbsp;មុខតំណែង៖</td>
                                <td><?php echo number_format($employee_data['ap']); ?>&nbsp;$</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>ប្រាក់បន្ថែម ផ្សេងៗ៖</td>
                                <td><?php echo number_format($employee_data['a1']); ?>&nbsp;$</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>ប្រាក់អតីតភាពការងារ៖</td>
                                <td><?php echo number_format($employee_data['sn']); ?>&nbsp;$</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>ប្រាក់លក់ថ្ងៃឈប់ប្រចាំឆ្នាំ៖</td>
                                <td><?php echo number_format($employee_data['sa']); ?>&nbsp;$</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>កែសម្រួល៖</td>
                                <td><?php echo number_format($employee_data['aj']); ?>&nbsp;$</td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="header">
                        <h4>ប្រាក់បៀវត្សន៏សរុប៖ <?php echo number_format($employee_data['t1']); ?>&nbsp;$</h4>
                    </div>
                    <table class="table table-striped ot-table">
                        <tbody>
                            <tr>
                                <td></td>
                                <td>អវត្តមាន៖</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>មាន&nbsp;ច្បាប់៖&nbsp;<?php echo number_format($employee_data['ABD']); ?>&nbsp;ថ្ងៃ&nbsp;<?php echo number_format($employee_data['ABH']); ?>&nbsp;ម៉ោង</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>គ្មានច្បាប់៖&nbsp;<?php echo number_format($employee_data['ABU']); ?>&nbsp;ម៉ោង</td>
                                <td></td>
                                <td></td>
                                <td><span>ចំនួនទឹកប្រាក់៖</span></td>
                                <td><?php echo number_format($employee_data['ABA']); ?>&nbsp;$</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td><span class="fon">ប្រាក់ឧបត្ថម្ភចូលឆ្នាំខ្មែរ&nbsp;៖</span></td>
                                <td><?php echo number_format($employee_data['AK']); ?>&nbsp;$</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td><span class="fon">ប្រាក់បៀវត្សន៏&nbsp;លើកទី&nbsp;១&nbsp;៖</span></td>
                                <td><?php echo number_format($employee_data['ADV']); ?>&nbsp;$</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td><span class="fon">ការផាកពិន័យផ្សេងៗ&nbsp;៖</span></td>
                                <td><?php echo number_format($employee_data['DUC']); ?>&nbsp;$</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td><span class="fon">ភាគទានសោធន&nbsp;៖</span></td>
                                <td><?php echo number_format($employee_data['PS']); ?>&nbsp;$</td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="header">
                        <h4>ប្រាក់បៀវត្សន៏&nbsp;លើកទី&nbsp;២៖ <?php echo number_format($employee_data['T2']); ?>&nbsp;$</h4>
                    </div>
                </div>
            </div>
        </div>
        <!-- Download Buttons -->
        <div class="mt-3 text-end">
            <a href="?action=download_pdf&key=<?php echo htmlspecialchars($short_key ?? ''); ?>" class="btn btn-success me-2">ទាញយកជា PDF</a>
            <button onclick="downloadImage()" class="btn btn-success">ទាញយកជារូបភាព</button>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <!-- JavaScript for Image Download -->
    <script>
        function downloadImage() {
            html2canvas(document.getElementById('salaryCard'), {
                scale: 2, 
                useCORS: true 
            }).then(canvas => {
                const link = document.createElement('a');
                link.download = 'salary_slip.png';
                link.href = canvas.toDataURL('image/png');
                link.click();
            });
        }
    </script>
</body>
</html>


<?php

if (isset($_GET['action']) && $_GET['action'] === 'download_pdf') {
    // Reprocess the URL data for PDF if enc is present
    if (isset($_GET['enc'])) {
        try {
            $compressed = decryptData($_GET['enc'], $secret_key, $method);
            if ($compressed === false) {
                throw new Exception('Decryption failed');
            }
            $json = gzuncompress($compressed);
            if ($json === false) {
                throw new Exception('Decompression failed');
            }
            $data = json_decode($json, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception('JSON decode failed: ' . json_last_error_msg());
            }
            // Update $employee_data with decrypted data
            foreach ($key_mapping as $short => $long) {
                if (isset($data[$short])) {
                    if ($short === 'ad' && $long === 'Abs_(Day)') {
                        $employee_data['Abs_(Day)'] = $data['ad'];
                    } elseif ($short === 'ah' && $long === 'Abs_(Hour)') {
                        $employee_data['Abs_(Hour)'] = $data['ah'];
                    } else {
                        $employee_data[$long] = $data[$short];
                    }
                }
            }
        } catch (Exception $e) {
            echo "<div class='alert alert-danger'>Error: " . htmlspecialchars($e->getMessage()) . "</div>";
            exit;
        }
    }
    require_once __DIR__ . '/../includes/pdf_generator.php';
    generateSalaryPDF($employee_data);
    exit;
}

?>