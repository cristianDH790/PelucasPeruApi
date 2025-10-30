<?php

namespace App\Helpers\Excel;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class ReporteExcelUsuarios
{
    public static function generarExcel($result, $nombreUsuario = 'Desconocido')
    {
        log_message('debug', 'Resultado reporte: ' . print_r($result, true));

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle("Reporte productos");

        $sheet->setCellValue("A1", "REPORTE DE PRODUCTOS");
        $sheet->mergeCells('A1:D1');

        $sheet->getStyle('A1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 17,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ]);

        $sheet->setCellValue("A2", "Usuario: " . $nombreUsuario);
        $sheet->mergeCells('A2:D2');

        $sheet->setCellValue("A3", "Fecha y hora: " . date("d/m/Y H:i:s"));
        $sheet->mergeCells('A3:D3');

        $headers = ["N째", "TIPO DOCUMENTO", "DOCUMENTO", "NOMBRE COMPLETO", "CORREO", "FECHA NACIMIENTO", "TELEFONO",  "ROL", "IMPORTE TOTAL",  "PEDIDOS",  "ESTADO",  "FECHA REGISTRO"];
        foreach ($headers as $index => $title) {
            $col = Coordinate::stringFromColumnIndex($index + 1);
            $sheet->setCellValue($col . '5', $title);
        }

        $fila = 6;
        $n = 1;

        foreach ($result as $productoData) {
            $sheet->setCellValue("A{$fila}", $n);
            $sheet->setCellValue("B{$fila}", $productoData['pDocumento']['nombre'] ?? '-');
            $sheet->setCellValue("C{$fila}", $productoData['documento'] ?? '-');
            $sheet->setCellValue("D{$fila}", $productoData['nombres'] . ' ' . $productoData['pApellido'] . ' ' . $productoData['sApellido']);
            $sheet->setCellValue("E{$fila}", $productoData['correo']);
            $sheet->setCellValue("F{$fila}", $productoData['fechaNacimiento'] ?? '-');
            $sheet->setCellValue("G{$fila}", $productoData['telefono'] ?? '-');
            $sheet->setCellValue("H{$fila}", $productoData['perfil']['nombre'] ?? '-');
            $sheet->setCellValue("I{$fila}", $productoData['pedidos'] ?? '-');
            $sheet->setCellValue("J{$fila}", $productoData['importeTotal'] ?? '-');
            $sheet->setCellValue("K{$fila}", $productoData['estado']['nombre'] ?? '-');
            $sheet->setCellValue("L{$fila}", isset($productoData['fecha']) ? date("d/m/Y H:i:s", strtotime($productoData['fecha'])) : '-');
            $n++;
            $fila++;
        }


        foreach (range('A', 'L') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }



        $sheet->getStyle("A5:L5")->applyFromArray([
            'font' => ['bold' => true],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'e4e4e4']],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT, 'vertical' => Alignment::VERTICAL_CENTER]
        ]);

        return $spreadsheet;
    }
}
