<?php

namespace Ichynul\Labuilder\Logic;

use Illuminate\Support\Collection;

class Export
{
    /**
     * Undocumented variable
     *
     * @var \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet|\PHPExcel_Worksheet
     */
    private $worksheet = null;

    /**
     * Undocumented function
     * @param string $title
     * @param array|Collection|\Generator $data
     * @param array $displayers
     * @return void
     */
    public function toCsv($title, $data, $displayers)
    {
        $title = str_replace([' ', '.', '!', '@', '＃', '$', '%', '^', '&', '*', '(', ')', '{', '}', '【', '】', '[', ']'], '', trim($title));
        ob_end_clean();

        $fname = '';
        if (request()->ajax()) {
            $dir = './uploads/export/' . date('Ymd') . '/';

            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
                file_put_contents(
                    $dir . 'index.html',
                    ''
                );
            }

            $fname = $dir . $title . "-" . date('Ymd-His') . ".csv";
            $fp = fopen($fname, 'w');
        } else {
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename=' . $title . "-" . date('Ymd-His') . ".csv");
            header('Cache-Control: max-age=0');
            $fp = fopen('php://output', 'a');
        }

        $headerData = [];

        foreach ($displayers as $key => $displayer) {
            $label = $displayer->getLabel();
            $label = preg_replace('/id/i', '编号', $label);
            $headerData[$key] = mb_convert_encoding($label, "GBK", "UTF-8");
        }

        fputcsv($fp, $headerData);

        //来源网络
        $num = 0;
        //每隔$limit行，刷新一下输出buffer，不要太大，也不要太小
        $limit = 5000;
        $row = null;
        $text = null;
        foreach ($data as $d) {
            $num++;
            //刷新一下输出buffer，防止由于数据过多造成问题
            if ($limit == $num) {
                if (ob_get_level() > 0) {
                    ob_flush();
                }
                flush();
                $num = 0;
            }
            $row = [];
            foreach ($displayers as $key => $displayer) {
                $text = $displayer->fill($d)->renderValue();
                $text = $this->replace($text);
                $row[$key] = mb_convert_encoding($text, "GBK", "UTF-8");
            }
            fputcsv($fp, $row);
        }
        unset($row, $text);
        fclose($fp);
        if ($fname) {
            return response()->json(['code' => 1, 'msg' => '文件已生成', 'data' => ltrim($fname, '.')]);;
        }
    }

    private function replace($text)
    {
        $text = preg_replace('/<[bh]r\s*\/?>/im', ' | ', $text);
        $text = preg_replace('/<i\s+[^<>]*?class=[\'\"]\w+\s+(\w+\-[\w\-]+)[\'\"][^<>]*?>(.*?)<\/i>/im', '$1', $text);
        $text = preg_replace('/<([a-zA-z]+?)\s+[^<>]*?>(.+?)<\/\1>/im', '$2', $text);
        $text = str_replace(['&nbsp;', '&gt;', '&lt;'], [' ', '>', '<'], $text);

        return $text;
    }

    /**
     * Undocumented function
     * @param string $title
     * @param array|Collection|\Generator $data
     * @param array $displayers
     * @param string $type
     * @return void
     */
    public function toExcel($title, $data, $displayers, $type = 'xls')
    {
        $title = str_replace([' ', '.', '!', '@', '＃', '$', '%', '^', '&', '*', '(', ')', '{', '}', '【', '】', '[', ']'], '', trim($title));

        ob_end_clean();
        $lib = '';

        $obj = null;
        if (class_exists('\\PhpOffice\\PhpSpreadsheet\\Spreadsheet')) {
            $obj = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $this->worksheet = $obj->getActiveSheet();
            $lib = 'PhpOffice';
        } else if (class_exists('\\PHPExcel')) {
            $obj = new \PHPExcel();
            $this->worksheet = $obj->getActiveSheet();
            $lib = 'PHPExcel';
        } else {
            return response()->json(['code' => 0, 'msg' => '未安装PHPExcel或PhpOffice', 'data' => '']);
        }

        $this->worksheet->setTitle($title);

        // 列标
        $list = [
            'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',
            'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', //够用就行
        ];

        // 填充第一行数据

        foreach ($displayers as $k => $displayer) {
            $label = $displayer->getLabel();
            $label = preg_replace('/id/i', '编号', $label);
            $this->worksheet->setCellValue($list[$k] . '1', $label);
        }
        $num = 0;
        $text = null;
        $c = 0;
        foreach ($data as $d) {
            $c = 0;
            foreach ($displayers as $key => $displayer) {
                $text = $displayer->fill($d)->renderValue();
                $text = $this->replace($text);
                if ($lib == 'PhpOffice') {
                    $this->worksheet->setCellValueExplicit($list[$c] . ($num + 2), $text, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                } else {
                    $this->worksheet->setCellValue($list[$c] . ($num + 2), $text, \PHPExcel_Cell_DataType::TYPE_STRING); //将其设置为文本格式
                }

                $c++;
            }
            $num++;
        }

        unset($text);
        $objWriter = null;
        if ($type == 'xls') {
            if ($lib == 'PhpOffice') {
                $objWriter = new \PhpOffice\PhpSpreadsheet\Writer\Xls($obj);
            } else {
                $objWriter = new \PHPExcel_Writer_Excel5($obj);
            }

            if (request()->ajax()) {
                $dir = './uploads/export/' . date('Ymd') . '/';

                if (!is_dir($dir)) {
                    if (mkdir($dir, 0755, true)) {
                        file_put_contents(
                            $dir . 'index.html',
                            ''
                        );
                    }
                }

                $fname = $dir . $title . "-" . date('Ymd-His') . ".xls";
                $objWriter->save($fname);

                return response()->json(['code' => 1, 'msg' => '文件已生成', 'data' => ltrim($fname, '.')]);;

            } else {
                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename="' . $title . "-" . date('Ymd-His') . '.xls');
                header('Cache-Control: max-age=0');
                $objWriter->save('php://output');
            }

        } elseif ($type == 'xlsx') {
            if ($lib == 'PhpOffice') {
                $objWriter = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($obj);
            } else {
                $objWriter = \PHPExcel_IOFactory::createWriter($obj, 'Excel2007');
            }

            if (request()->ajax()) {
                $dir = './uploads/export/' . date('Ymd') . '/';
                if (!is_dir($dir)) {
                    if (mkdir($dir, 0755, true)) {
                        file_put_contents(
                            $dir . 'index.html',
                            ''
                        );
                    }
                }

                $fname = $dir . $title . "-" . date('Ymd-His') . ".xlsx";
                $objWriter->save($fname);

                return response()->json(['code' => 1, 'msg' => '文件已生成', 'data' => ltrim($fname, '.')]);;

            } else {
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="' . $title . "-" . date('Ymd-His') . '.xlsx');
                header('Cache-Control: max-age=0');
                $objWriter->save('php://output');
            }
        }
    }
}
