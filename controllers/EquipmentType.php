<?php

defined('BASEPATH') or exit('No direct script access allowed');

abstract class EquipmentType extends AdminController{
  // Abstract method with an argument
  abstract protected function prefixName($name);
}

class Equipment extends EquipmentType {
  public function prefixName($name) {
    if ($name == "John Doe") {
      $prefix = "Mr.";
    } elseif ($name == "Jane Doe") {
      
      $prefix = [
        'nama_pesawat' => '',
        'pabrik_pembuat' => '',
        'merk' => '',
        'instalatir' => '',
        'tahun_pemasangan' => '',
        'mcfa_/_merk' => '',
        'jumlah_smoke_detector' => '',
        'jumlah_heat_etector' => '',
        'jumlah_titik_panggil_manual' => '',
        'jumlah_alarm_bell' => '',
        'jumlah_alarm_lamp' => ''
      ];
    } else {
      $prefix = "";
    }
    return "{$prefix} {$name}";
  }
}