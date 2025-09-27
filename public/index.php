<?php
// public/index.php
// require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../includes/encryption.php';
require_once __DIR__ . '/../config/config.php';

// $secret_key = $config['encryption_key'] ?? 'your_secret_key_32_chars_long';
// $method = 'aes-256-cbc';

// Initialize employee_data with valid keys
$employee_data = [
    'Emp_ID' => '',
    'KhmerName' => '',
    'SalaryDTKH' => '',
    'Basic' => 0.00,
    'Total_Normal_OT' => 0.00,
    'Normal_Amount' => 0.00,
    'Aft_Night_OT' => 0.00,
    'OT_Aft_Night' => 0.00,
    'Holiday_Normal_OT' => 0.00,
    'Total_HOT' => 0.00,
    'Night_Wage' => 0.00,
    'Alw_Att' => 0.00,
    'Alw_Housing' => 0.00,
    'Alw_GSTARS' => 0.00,
    'Alw_License' => 0.00,
    'Alw_Position' => 0.00,
    'Alw_Additional' => 0.00,
    'Seniority' => 0.00,
    'SaleAL' => 0.00,
    'Adjust' => 0.00,
    'Total_1' => 0.00,
    'Abs_Day' => 0.00,        
    'Abs_AL' => 0.00,
    'Abs_SL' => 0.00,
    'Abs_SP' => 0.00,
    'Abs_UP' => 0.00,
    'Abs_A' => 0.00,
    'Abs_Unpaid' => 0.00,     
    'Abs_Amount' => 0.00,
    'Alw_KHNY' => 0.00,
    'Advance' => 0.00,
    'Deduct' => 0.00,
    'Pension' => 0.00,
    'Total_2' => 0.00
];

// Updated key mapping to match generate.php
$key_mapping = [
    'e' => 'Emp_ID',
    'k' => 'KhmerName',
    's' => 'SalaryDTKH',
    'b' => 'Basic',
    'tn' => 'Total_Normal_OT',
    'na' => 'Normal_Amount',
    'an' => 'Aft_Night_OT',
    'oa' => 'OT_Aft_Night',
    'hn' => 'Holiday_Normal_OT',
    'th' => 'Total_HOT',
    'nw' => 'Night_Wage',
    'aa' => 'Alw_Att',
    'ah' => 'Alw_Housing',
    'ag' => 'Alw_GSTARS',
    'al' => 'Alw_License',
    'ap' => 'Alw_Position',
    'a1' => 'Alw_Additional',
    'sn' => 'Seniority',
    'sa' => 'SaleAL',
    'aj' => 'Adjust',
    't1' => 'Total_1',
    'a2' => 'Abs_Day',      
    'a3' => 'Abs_AL',    
    'a4' => 'Abs_SL',
    'a5' => 'Abs_SP',
    'a6' => 'Abs_UP',
    'a7' => 'Abs_A',
    'au' => 'Abs_Unpaid',   
    'am' => 'Abs_Amount',
    'ak' => 'Alw_KHNY',
    'av' => 'Advance',
    'dd' => 'Deduct',
    'pn' => 'Pension',
    't2' => 'Total_2'
];

// Get the encrypted parameter from URL
$enc = isset($_GET['enc']) ? $_GET['enc'] : '';

if ($enc) {
    try {
        // Decrypt the data
        // $compressed = decryptData($enc, $secret_key, $method);
        // if ($compressed === false) {
        //     throw new Exception('Decryption failed');
        // }

        // Test DecodeStr func
        $Decodedata = decodeString($enc);
        if ($Decodedata === false) {
            throw new Exception('Decryption failed');
        }
        $data = json_decode($Decodedata,  true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('JSON decode failed: ' . json_last_error_msg());
         echo $Decodedata ;
        }

        // Decompress the data
        // $json = gzuncompress($compressed);
        // if ($json === false) {
        //     throw new Exception('Decompression failed');
        // }

        // // Decode JSON
        // $data = json_decode($json, true);
        // if (json_last_error() !== JSON_ERROR_NONE) {
        //     throw new Exception('JSON decode failed: ' . json_last_error_msg());
        // }

        // Map short keys to long keys
        foreach ($key_mapping as $short => $long) {
            if (isset($data[$short])) {
                $employee_data[$long] = $data[$short];
            }
        }
    

        // Validate all required keys are present
        // foreach ($employee_data as $key => $value) {
        //     if ($value === '' || $value === 0) {
        //         throw new Exception("Missing or invalid data for key: $key");
        //     }
        // }
    } catch (Exception $e) {
        echo "<div class='alert alert-danger'>Error: " . htmlspecialchars($e->getMessage()) . "</div>";
        exit;
    }
} else {
    // Use mock data only if no enc parameter is provided
    $employee_data = [
        'Emp_ID' => '',
        'KhmerName' => '',
        'SalaryDTKH' => '',
        'Basic' => 0.00,
        'Total_Normal_OT' => 0.00,
        'Normal_Amount' => 31.5,
        'Aft_Night_OT' => 31.5,
        'OT_Aft_Night' => 0.00,
        'Holiday_Normal_OT' => 0.00,
        'Total_HOT' => 0.00,
        'Night_Wage' => 0.00,
        'Alw_Att' => 0.00,
        'Alw_Housing' => 0.00,
        'Alw_GSTARS' => 0.00,
        'Alw_License' => 0.00,
        'Alw_Position' => 0.00,
        'Alw_Additional' => 0.00,
        'Seniority' => 0.00,
        'SaleAL' => 0.00,
        'Adjust' => 0.00,
        'Total_1' => 0.00,
        'Abs_Day' => 0.00,        
        'Abs_AL' => 0.00,
        'Abs_SL' => 0.00,
        'Abs_SP' => 0.00,
        'Abs_UP' => 0.00,
        'Abs_A' => 0.00,
        'Abs_Unpaid' => 0.00,     
        'Abs_Amount' => 0.00,
        'Alw_KHNY' => 0.00,
        'Advance' => 0.00,
        'Deduct' => 0.00,
        'Pension' => 0.00,
        'Total_2' => 0.00
    ];
}

if (isset($_GET['action']) && $_GET['action'] === 'download_pdf') {
    // Use the already populated $employee_data
    require_once __DIR__ . '/../includes/pdf_generator.php';
    generateSalaryPDF($employee_data);
    exit;
}
?>

<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salary Check</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Khmer:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Khmer&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./css/style.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    
</head>
<body>
    <div class="container my-5" id="capture-area">
        <div class="card" id="salaryCard">
            <div class="  card-header text-center">
                <h1 style="font-weight: 800; font-size: 40px" class="mb-0">ព័ត៌មានប្រាក់បៀវត្សន៍</h1>
            </div>
            <div class="card-body">
                <div class="vertical-line-container">
                    <div class="upper-content">
                        <div class="employee-info row gy-2 mb-4">
                            <div class="col-12 col-md-4">
                                <strong style="font-size: 20px">ល.អ.ត ៖&nbsp;<?php echo htmlspecialchars($employee_data['Emp_ID']); ?></strong>
                            </div>
                            <div class="col-12 col-md-4">
                                <strong style="font-size: 20px">ឈ្មោះ ៖&nbsp;<?php echo htmlspecialchars($employee_data['KhmerName']); ?></strong>
                            </div>
                            <div class="col-12 col-md-4">
                                <strong style="font-size: 20px;">បៀវត្សន៍ខែ ៖&nbsp;<?php echo htmlspecialchars($employee_data['SalaryDTKH']); ?></strong> 
                            </div>
                        </div>
                        <div class="header text-center mb-4">
                            <h4 class="mb-0">ប្រាក់ខែគោល ៖ <?php echo number_format($employee_data['Basic'], 2);?> $</h4>
                        </div>
                    </div>
                    <div class="vertical-line"></div>
                    <div class="lower-content">
                        <div class="table-responsive">
                            <table class="table table-striped ot-table">
                                <tbody>
                                    <tr>
                                        <td></td>
                                        <td class="align">ថែមម៉ោង៖</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td class="disable"></td>
                                        <td style="text-align: right; width: 266.844px;">ពេលថ្ងៃ៖&nbsp;&nbsp;<?php echo number_format($employee_data['Total_Normal_OT'],1); ?>&nbsp;ម៉ោង</td>
                                        <td></td>
                                        <td></td>
                                        <td style="text-align: right; width: 466.844px;">ចំនួនទឹកប្រាក់៖</td>
                                        <td style="text-align: right;" ><?php echo number_format($employee_data['Normal_Amount'], 2); ?>&nbsp;$</td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td style="text-align: right;">ពេលយប់៖&nbsp;&nbsp;<?php echo number_format($employee_data['Aft_Night_OT'], 1); ?>&nbsp;ម៉ោង</td>
                                        <td></td>
                                        <td></td>
                                        <td style="text-align: right; width: 466.844px;">ចំនួនទឹកប្រាក់៖</td>
                                        <td style="text-align: right;" ><?php echo number_format($employee_data['OT_Aft_Night'], 2); ?>&nbsp;$</td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td style="text-align: right;">ថ្ងៃឈប់៖&nbsp;&nbsp;<?php echo number_format($employee_data['Holiday_Normal_OT']); ?>&nbsp;ម៉ោង</td>
                                        <td></td>
                                        <td></td>
                                        <td style="text-align: right; width: 466.844px;">ចំនួនទឹកប្រាក់៖</td>
                                        <td style="text-align: right;" ><?php echo number_format($employee_data['Total_HOT'], 2); ?>&nbsp;$</td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td style="text-align: right; width: 466.844px;">ប្រាក់បន្ថែម&nbsp;វេនយប់៖</td>
                                        <td style="text-align: right;" ><?php echo number_format($employee_data['Night_Wage'], 2); ?>&nbsp;$</td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td style="text-align: right; width: 466.844px;">ប្រាក់ឧបត្ថម្ភ&nbsp;វត្តមាន៖</td>
                                        <td style="text-align: right;" ><?php echo number_format($employee_data['Alw_Att'], 2); ?>&nbsp;$</td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td style="text-align: right; width: 466.844px;">ប្រាក់ឧបត្ថម្ភ&nbsp;ការស្នាក់នៅ៖</td>
                                        <td style="text-align: right;" ><?php echo number_format($employee_data['Alw_Housing'], 2); ?>&nbsp;$</td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td style="font-weight: bold; text-align: right; width: 306.844px; ">សរុប&nbsp;ការឈប់សម្រាកមានប្រាក់ឈ្នួល៖&nbsp;<?php echo number_format($employee_data['Abs_Day']); ?>&nbsp;ថ្ងៃ</td>
                                        <td></td>
                                        <td></td>
                                        <td style="text-align: right; width: 466.844px;" >ប្រាក់បន្ថែម&nbsp;(G-STARS)៖</td>
                                        <td style="text-align: right;" ><?php echo number_format($employee_data['Alw_GSTARS'], 2); ?>&nbsp;$</td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td style="font-weight: bold; text-align: right; width: 306.844px; ">ការឈប់សម្រាក&nbsp;ប្រចាំឆ្នាំ(AL)៖&nbsp;<?php echo number_format($employee_data['Abs_AL']); ?>&nbsp;ថ្ងៃ</td>
                                        <td></td>
                                        <td></td>
                                        <td style="text-align: right; width: 466.844px;">ប្រាក់បន្ថែម License៖</td>
                                        <td style="text-align: right;" ><?php echo number_format($employee_data['Alw_License'], 2); ?>&nbsp;$</td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td style="font-weight: bold; text-align: right; width: 306.844px; ">ការឈប់សម្រាកឈឺ​(SL)៖&nbsp;<?php echo number_format($employee_data['Abs_SL']); ?>&nbsp;ថ្ងៃ</td>
                                        <td></td>
                                        <td></td>
                                        <td style="text-align: right; width: 466.844px;">ប្រាក់បន្ថែម&nbsp;មុខតំណែង៖</td>
                                        <td style="text-align: right;" ><?php echo number_format($employee_data['Alw_Position'], 2); ?>&nbsp;$</td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td style="font-weight: bold; text-align: right; width: 306.844px; ">ការឈប់សម្រាកពិសេស(SP)៖&nbsp;<?php echo number_format($employee_data['Abs_SP']); ?>&nbsp;ថ្ងៃ</td>
                                        <td></td>
                                        <td></td>
                                        <td style="text-align: right; width: 466.844px;">ប្រាក់បន្ថែម ផ្សេងៗ៖</td>
                                        <td style="text-align: right;" ><?php echo number_format($employee_data['Alw_Additional'], 2); ?>&nbsp;$</td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td style="text-align: right; width: 466.844px;">​ ប្រាក់អតីតភាពការងារ៖</td>
                                        <td style="text-align: right;" ><?php echo number_format($employee_data['Seniority'], 2); ?>&nbsp;$</td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td style="text-align: right; width: 466.844px;">ប្រាក់លក់ថ្ងៃឈប់ប្រចាំឆ្នាំ៖</td>
                                        <td style="text-align: right;" ><?php echo number_format($employee_data['SaleAL'], 2); ?>&nbsp;$</td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td style="text-align: right; width: 466.844px;">កែសម្រួល៖</td>
                                        <td style="text-align: right;" ><?php echo number_format($employee_data['Adjust'], 2); ?>&nbsp;$</td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="header text-center mb-4">
                                <h4 class="mb-0">ប្រាក់បៀវត្សន៏សរុប៖ <?php echo number_format($employee_data['Total_1'], 2); ?>&nbsp;$</h4>
                            </div>
                            <table class="table table-striped ot-table">
                                <tbody>
                                    <tr>
                                        <td></td>
                                        <td style="color: red; font-weight: bold; text-align: right; width: 306.844px;">សរុប&nbsp;ការឈប់សម្រាក&nbsp;គ្មានប្រាក់ឈ្នួល៖&nbsp;<?php echo number_format($employee_data['Abs_Unpaid']); ?>&nbsp;ថ្ងៃ</td>
                                        <td></td>
                                        <td></td>
                                        <td style="text-align: right; width: 466.844px;">ចំនួនទឹកប្រាក់&nbsp;៖</td>
                                        <td style="text-align: right; width: 305.578x;"><?php echo number_format($employee_data['Abs_Amount'], 2); ?>&nbsp;$</td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td style="color: red; text-align: right; width: 306.844px;">ការឈប់សម្រាក&nbsp;គ្មានប្រាក់ឈ្នួល(UP)៖&nbsp;<?php echo number_format($employee_data['Abs_UP']); ?>&nbsp;ថ្ងៃ</td>
                                        <td></td>
                                        <td></td>
                                        <td style="text-align: right; width: 466.844px;">ប្រាក់ឧបត្ថម្ភ&nbsp;ចូលឆ្នាំខ្មែរ&nbsp;៖</td>
                                        <td style="text-align: right; width: 305.578x;"><?php echo number_format($employee_data['Alw_KHNY'], 2); ?>&nbsp;$</td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td style="color: red; text-align: right; width: 306.844px;">អវត្តមាន&nbsp;គ្មានការអនុញ្ញាត(A)&nbsp;៖&nbsp;<?php echo number_format($employee_data['Abs_A']); ?>&nbsp;ថ្ងៃ</td>
                                        <td></td>
                                        <td></td>
                                        <td style="text-align: right; width: 466.844px;">ប្រាក់បៀវត្សន៍&nbsp;លើកទី១៖</td>
                                        <td style="text-align: right; width: 305.578x;"><?php echo number_format($employee_data['Advance'], 2); ?>&nbsp;$</td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td style="text-align: right; width: 466.844px;">ភាគទានសោធន៖</td>
                                        <td style="text-align: right; width: 305.578x;"><?php echo number_format($employee_data['Pension'], 2); ?>&nbsp;$</td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td style="text-align: right; width: 466.844px;">​ ការផាកពិន័យផ្សេងៗ៖</td>
                                        <td style="text-align: right; width: 305.578x;"><?php echo number_format($employee_data['Seniority'], 2); ?>&nbsp;$</td>
                                    </tr>
                                  
                                </tbody>
                            </table>
                            <div class="header text-center mb-4">
                                <h4 class="mb-0">ប្រាក់បៀវត្សន៏&nbsp;លើកទី&nbsp;២៖ <?php echo number_format($employee_data['Total_2'], 2); ?>&nbsp;$</h4>
                            </div>
                            <div>
                                <p style="color: #af48af;">
                                    *&nbsp;ការឈប់សម្រាកពិសេស​(SP)៖&nbsp;រៀបអាពាហ៍ពិពាហ៍ផ្ទាល់ខ្លួន​ ឬ កូនបង្កើត ២. ភរិយាសម្រាលកួន ៣. ភរិយា ឬស្វាមី. កួនបង្កើត. ឪពុកម្ដាយបង្កើត មានជំងឺឬ ទទួលមរណះភាព។ 
                                </p>
                                <p style="color: #af48af;">*&nbsp;ការឈប់សម្រាកពិសេស&nbsp;នឹងត្រូវកាត់កងនៅចុងឆ្នាំសារពើពន្ធនីមួយៗ&nbsp;ជាមួយនឹងថ្ងៃឈប់សម្រាកប្រចាំឆ្នាំ&nbsp;(ប្រសិនបើថ្ងៃឈប់សម្រាកប្រចាំឆ្នាំនៅសល់)។</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-3 text-end">
            <a href="?action=download_pdf&enc=<?php echo urlencode($enc); ?>" class="btn btn-success me-2">ទាញយកជា PDF</a>
            <button onclick="captureFullScreenWebPage() ;" class="btn btn-success">ទាញយកជារូបភាព</button>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script>
    // function downloadImage() {

    //     window.scrollTo(0, 0);

    //     html2canvas(document.body, {
    //         scale: 2, 
    //         useCORS: true,
    //     }).then(canvas => {
    //         const link = document.createElement('a');
    //         link.download = 'salary_slip.png';
    //         link.href = canvas.toDataURL('image/png');
    //         document.body.appendChild(link);
    //         link.click();
    //         document.body.removeChild(link);3
    //     });
    // }
    // window.onload = function() {
    //         downloadImage();
    //     };
    
    // Function to capture a webpage 

function captureFullScreenWebPage() {
    if (typeof html2canvas === 'undefined') {
        console.error('html2canvas library is required. Please include it in your project.');
        return;
    }

    
    const element = document.body;

    
    html2canvas(element, {
        width: 1080, 
        height: 1920, 
        windowWidth: 1080,
        windowHeight: 1920,
        scrollX: 0,
        scrollY: 0,
        useCORS: true,
        scale: window.devicePixelRatio 
    }).then(canvas => {
        const image = canvas.toDataURL('image/png');

        const link = document.createElement('a');
        link.href = image;
        link.download = 'fullpage-capture.png';
        document.body.appendChild(link); // required for mobile Safari
        link.click();
        document.body.removeChild(link);
    }).catch(error => {
        console.error('Error capturing the full screen:', error);
    });
}


    </script>
</body>
</html>