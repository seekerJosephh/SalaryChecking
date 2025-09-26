<?php
// includes/pdf_generator.php
require_once __DIR__ . '/../vendor/autoload.php';
use Mpdf\Mpdf;

function generateSalaryPDF($employee_data) {
    // Define directories
    $tempDir = __DIR__ . '/../tmp';
    $fontDir = __DIR__ . '/../tmp/mpdf/ttfonts';

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

    // mPDF configuration
    $mpdfConfig = [
        'mode' => 'utf-8',
        'format' => 'A4',
        'orientation' => 'P',
        'tempDir' => $tempDir,
        'fontDir' => [$fontDir],
        'fontdata' => [
            'battambang' => [
                'R' => 'KhmerOS.ttf',
                'B' => 'KhmerOS.ttf',
                'useOTL' => 0xFF,
            ],
            'khmermuol' => [
                'R' => 'KhmerOSmuol.ttf',
                'useOTL' => 0xFF,
            ],
            'notokhmer' => [
                'R' => 'NotoSansKhmer-Regular.ttf',
                'B' => 'NotoSansKhmer.ttf',
                'useOTL' => 0xFF,
            ],
        ],
        'default_font' => 'battambang',
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

        // CSS for styling
        $css = '
        body { font-family: "battambang", sans-serif; font-size: 10pt; color: #000;}
        .container { max-width: 900px; margin: 0 auto; padding: 5px; }
        .card { border: 1px solid #000; padding: 5px; }
        .header1 { text-align: center; font-size: 14pt; font-weight: bold; }
        .header { text-align: center; font-size: 12pt; font-weight: bold; ; }
        .employee-info { margin-bottom: 5px; }
        .table { width: 100%; border-collapse: collapse; margin: 5px 0; }
        .table td { padding: 5px; text-align: left; vertical-align: top; border: 1px solid #000; }
        .table-striped tbody tr:nth-of-type(odd) { background-color: #f9f9f9; }
        .disable { border: none; }
        .fon { font-weight: bold; }
        .mid { text-align: center;}
        
        ';

        // HTML content with updated keys
        $html = '
        <div class="container">
            <div class="card">
                <div class="header1">
                    <h4>ព័ត៌មានប្រាក់បៀវត្សន៏</h4>
                </div>
                    <div class="card-body">
                        <div class="employee-info row gy-2 mb-4">
                            <div class="col-12 col-md-4">
                                <strong>ល.អ.ត ៖</strong> ' . htmlspecialchars($employee_data['Emp_ID']) . '
                            </div>
                            <div  class="col-12 col-md-4">
                                <strong>ឈ្មោះ ៖</strong> ' . htmlspecialchars($employee_data['KhmerName']) . '
                            </div>
                            <div class="col-12 col-md-4">
                                <strong>បៀវត្សន៏ខែ ៖</strong>  ' . htmlspecialchars($employee_data['SalaryDTKH']) . '
                            </div>
                        </div>
                    </div>
                    <div class="header">
                        <h4>ប្រាក់ខែគោល ៖ ' . number_format($employee_data['Basic'], 2) . ' $</h4>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped ot-table">
                            <tbody>
                                <tr><td colspan="2" class="fon">ថែមម៉ោង</td><td></td><td></td></tr>
                                <tr><td>ពេលថ្ងៃ</td><td>' . number_format($employee_data['Total_Normal_OT']) . '&nbsp;ម៉ោង</td><td>ចំនួនទឹកប្រាក់</td><td>' . number_format($employee_data['Normal_Amount'], 2) . '&nbsp;$</td></tr>
                                <tr><td>ពេលយប់</td><td>' . number_format($employee_data['Aft_Night_OT']) . '&nbsp;ម៉ោង</td><td>ចំនួនទឹកប្រាក់</td><td>' . number_format($employee_data['OT_Aft_Night'], 2 ) . '&nbsp;$</td></tr>
                                <tr><td>ថ្ងៃឈប់</td><td>' . number_format($employee_data['Holiday_Normal_OT']) . '&nbsp;ម៉ោង</td><td>ចំនួនទឹកប្រាក់</td><td>' . number_format($employee_data['Total_HOT'], 2) . '&nbsp;$</td></tr>
                                <tr><td colspan="2"></td><td>ប្រាក់បន្ថែម&nbsp;វេនយប់</td><td>' . number_format($employee_data['Night_Wage'], 2) . '&nbsp;$</td></tr>
                                <tr><td colspan="2"></td><td>ប្រាក់ឧបត្ថម្ភ&nbsp;វត្តមាន</td><td>' . number_format($employee_data['Alw_Att'], 2) . '&nbsp;$</td></tr>
                                <tr><td colspan="2"></td><td>ប្រាក់ឧបត្ថម្ភ&nbsp;ការស្នាក់នៅ</td><td>' . number_format($employee_data['Alw_Housing'], 2) . '&nbsp;$</td></tr>
                                <tr><td>សរុប&nbsp;ការឈប់សម្រាក&nbsp;មានប្រាក់ឈ្នួល៖&nbsp;</td><td>' . number_format($employee_data['Abs_Day']) . '&nbsp;ថ្ងៃ</td><td>ប្រាក់បន្ថែម ជីស្ដា(G-STARS)</td><td>' . number_format($employee_data['Alw_GSTARS'], 2) . '&nbsp;$</td></tr>
                                <tr><td>ការឈប់សម្រាក&nbsp;ប្រចាំឆ្នាំ(AL)៖&nbsp;</td><td>' . number_format($employee_data['Abs_AL']) . '&nbsp;ថ្ងៃ</td><td>ប្រាក់បន្ថែម License</td><td>' . number_format($employee_data['Alw_License'], 2) . '&nbsp;$</td></tr>
                                <tr><td>ការឈប់សម្រាកឈឺ​(SL)៖&nbsp;</td><td>' . number_format($employee_data['Abs_SL']) . '&nbsp;ថ្ងៃ</td><td>ប្រាក់បន្ថែម&nbsp;មុខតំណែង</td><td>' . number_format($employee_data['Alw_Position'], 2) . '&nbsp;$</td></tr>
                                <tr><td >ការឈប់សម្រាកពិសេស(SP)៖&nbsp;</td><td>' . number_format($employee_data['Abs_SP']) . '&nbsp;ថ្ងៃ</td><td>ប្រាក់បន្ថែម ផ្សេងៗ</td><td>' . number_format($employee_data['Alw_Additional'], 2) . '&nbsp;$</td></tr>
                                <tr><td colspan="2"></td><td>ប្រាក់អតីតភាពការងារ</td><td>' . number_format($employee_data['Seniority'], 2) . '&nbsp;$</td></tr>
                                <tr><td colspan="2"></td><td>ប្រាក់លក់ថ្ងៃឈប់ប្រចាំឆ្នាំ</td><td>' . number_format($employee_data['SaleAL'], 2) . '&nbsp;$</td></tr>
                                <tr><td colspan="2"></td><td>កែសម្រួល</td><td>' . number_format($employee_data['Adjust'], 2) . '&nbsp;$</td></tr>
                            </tbody>
                        </table>
                        <div class="header">
                            <h4>ប្រាក់បៀវត្សន៏សរុប៖ ' . number_format($employee_data['Total_1'], 2) . '&nbsp;$</h4>
                        </div>
                            <table class="table table-striped ot-table">
                            <tbody>  
                                <tr><td>សរុប&nbsp;ការឈប់សម្រាក&nbsp;គ្មានប្រាក់ឈ្នួល៖</td><td>' . number_format($employee_data['Abs_Unpaid']) . '&nbsp;&nbsp;&nbsp;ថ្ងៃ</td><td>ចំនួនទឹកប្រាក់</td><td>' . number_format($employee_data['Abs_Amount'], 2) . '&nbsp;$</td></tr>
                                <tr><td>ការឈប់សម្រាក&nbsp;គ្មានប្រាក់ឈ្នួល(UP)៖</td><td>' . number_format($employee_data['Abs_UP']) . '&nbsp;&nbsp;&nbsp;ថ្ងៃ</td><td>ប្រាក់ឧបត្ថម្ភចូលឆ្នាំខ្មែរ</td><td>' . number_format($employee_data['Alw_KHNY']) . '&nbsp;$</td></tr>
                                <tr><td>អវត្តមាន&nbsp;គ្នានការអនុញ្ញាត(A)៖</td><td>' . number_format($employee_data['Abs_A']) . '&nbsp;&nbsp;&nbsp;ថ្ងៃ</td><td>ប្រាក់បៀវត្សន៏&nbsp;លើកទី&nbsp;១</td><td>' . number_format($employee_data['Advance']) . '&nbsp;$</td></tr>
                                <tr><td colspan="2"></td><td>ភាគទានសោធន៖&nbsp;</td><td>' . number_format($employee_data['Pension'], 2) . '&nbsp;$</td></tr>
                                <tr><td colspan="2"></td><td>ការផាកពិន័យផ្សេងៗ៖&nbsp;</td><td>' . number_format($employee_data['Deduct'], 2) . '&nbsp;$</td></tr>
                                
                            </tbody>
                        </table>
                       
                        <div class="header">
                            <h4>ប្រាក់បៀវត្សន៏&nbsp;លើកទី&nbsp;២៖ ' . number_format($employee_data['Total_2'], 2) . '&nbsp;$</h4>
                            
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
';

        // Write CSS and HTML
        $mpdf->WriteHTML($css, \Mpdf\HTMLParserMode::HEADER_CSS);
        $mpdf->WriteHTML($html, \Mpdf\HTMLParserMode::HTML_BODY);

        // Generate filename
        $filename = 'salary_slip_' . preg_replace('/[^a-zA-Z0-9_-]/', '_', ($employee_data['Emp_ID'] ?? 'unknown')) . '.pdf';

        // Output PDF
        $mpdf->Output($filename, 'D');

    } catch (\Mpdf\MpdfException $e) {
        error_log('mPDF Error: ' . $e->getMessage());
        http_response_code(500);
        echo '<div class="alert alert-danger">PDF Generation Error: ' . htmlspecialchars($e->getMessage()) . '</div>';
        exit;
    }
}
?>