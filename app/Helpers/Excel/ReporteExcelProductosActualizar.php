<?php

namespace App\Helpers\Excel;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class ReporteExcelProductosActualizar
{
    public static function generarExcel($result)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle("Productos");

        // 🔹 Encabezados actualizados
        $headers = [
            "CODIGO",
            "PRODUCTO",
            "CATEGORIAS",
            "DESTACADO",
            "COMPLEMENTO",
            "ESTADO",
            "PESO",
            "PRECIO LISTA",
            "PRECIO VENTA",
            "RESUMEN",
            "CONTENIDO",
            "COLOR",
            "STOCK"
        ];

        // 🔹 Mapeo de encabezados a claves reales del array
        $keysMap = [
            "CODIGO"         => "codigo",
            "PRODUCTO"       => "nombre",
            "CATEGORIAS"     => "categoria",
            "DESTACADO"      => "destacado",
            "COMPLEMENTO"    => "complemento",
            "ESTADO"         => "estado",
            "PESO"           => "peso",
            "PRECIO LISTA"   => "precioLista",
            "PRECIO VENTA"   => "precioVenta",
            "RESUMEN"        => "resumen",
            "CONTENIDO"      => "contenido",
            "COLOR"          => "color", // <- aquí se usará codigoproductocolor que envías desde el controlador
            "STOCK"          => "stock",
        ];

        // 🔹 Escribir encabezados en la fila 1
        $colIndex = 1;
        foreach ($headers as $header) {
            $col = Coordinate::stringFromColumnIndex($colIndex);
            $sheet->setCellValue("{$col}1", strtoupper($header));
            $colIndex++;
        }

        // 🔹 Estilo de cabecera
        $lastCol = Coordinate::stringFromColumnIndex(count($headers));
        $sheet->getStyle("A1:{$lastCol}1")->applyFromArray([
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'E0E0E0']
            ],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
        ]);

        // 🔹 Escribir datos desde la fila 2
        $fila = 2;
        foreach ($result as $producto) {
            $colIndex = 1;
            foreach ($headers as $header) {
                $col = Coordinate::stringFromColumnIndex($colIndex);
                $campo = $keysMap[$header] ?? strtolower($header);
                $valor = $producto[$campo] ?? '';
                $sheet->setCellValue("{$col}{$fila}", $valor);
                $colIndex++;
            }

            // 📌 Formato de moneda para precios
            $sheet->getStyle("H{$fila}:I{$fila}")
                ->getNumberFormat()
                ->setFormatCode('"S/ "#,##0.00');

            $fila++;
        }

        // 🔹 Bordes de toda la tabla
        $sheet->getStyle("A1:{$lastCol}" . ($fila - 1))->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
        ]);

        // 🔹 Ajustar ancho de columnas automáticamente
        foreach (range('A', $lastCol) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        return $spreadsheet;
    }
}
