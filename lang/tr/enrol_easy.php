<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.
/**
 * Allows course enrolment via a simple text code.
 *
 * @package   enrol_easy
 * @copyright 2017 Dearborn Public Schools
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
$string['pluginname'] = 'Easy enrollments';
$string['pluginname_desc'] = 'Derslere kolay kayıt olmayı sağlayan bir eklentidir.';
$string['enrolform_course_code'] = 'Kurs Kayıt Kodu';
$string['enrolform_submit'] = 'Kayıt Ol';
$string['enrolform_heading'] = 'Bir derse kayıt ol';
$string['enrolform_pagetitle'] = 'Bir derse kayıt ol';
$string['header_coursecodes'] = 'Kayıt kodları';
$string['status'] = 'Aktif';
$string['status_help'] = 'Ders kaydını açmak için "Aktif", kapatmak için "Pasif" olarak ayarlayın.';
$string['enrolstartdate'] = 'Kayıt Başlangıç tarihi';
$string['enrolstartdate_help'] = 'Bu tarihten önce kayıt yapılmaz.';
$string['enrolenddate'] = 'Kayıt Bitiş Tarihi';
$string['enrolenddate_help'] = 'Bu tarihten sonra kayıt yapılmaz.';
$string['regenerate_codes'] = 'Kodları tekrar üret';
$string['regenerate_codes_help'] = 'Bu kutuyu işaretleyin ve "Değişiklikleri Kaydet" tuşuna basın. Yeni kodlar üretilecektir.';
$string['qrenabled'] = 'QR Kod kullanımını Aktif';
$string['qrenableddesc'] = 'QR Kod kullanarak kayıt özelliği aktif hale getirilir';
$string['easy:unenrolself'] = 'Ders kaydınızı iptal edin.';
$string['easy:config'] = 'Easy Enrollment Ayarları';
$string['easy:delete'] = 'Easy Enrollment Ayarlarını Silin';
$string['easy:manage'] = 'Easy Enrollment Ayarlarını Yönetin';
$string['easy:unenrol'] = 'Easy Enrollment Kayıtlarını İptal Edin';
$string['unenrolselfconfirm'] = '"{$a}" dersinden kaydınızı silmek istediğinize emin misiniz??';
$string['error_disabled_global'] = 'Easy enrollment Site Genelinde iptal edilmiş durumda';
$string['error_disabled_global'] = 'Easy enrollment bu ders için iptal edilmiş durumda.';
$string['error_enrolstartdate'] = 'Bu ders için kayıt henüz başlamadı.';
$string['error_enrolenddate'] = 'Bu ders için kayıt sona erdi.';
$string['error_invalid_code'] = 'Hatalı kayıt kodu.';
