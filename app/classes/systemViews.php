<?php
namespace App\classes;
use App\Tbl_patient;

use Illuminate\Support\Facades\DB;

class systemViews{
	
	public  static function getTreatmentCharts(){
    $sql="CREATE OR REPLACE VIEW `vw_treatment_charts` AS (SELECT t1.*,t1.timedosage AS time_given,t1.created_at AS time_recorded,t7.bed_name,t8.ward_name,t6.item_name,t2.admission_date,
                t4.medical_record_number,CONCAT(t4.first_name,' ',t4.middle_name,' ',t4.last_name) AS patient_name,t5.bed_id,t5.ward_id,
                t2.facility_id ,t3.name AS nurse_name,t9.dose
          FROM `tbl_ipdtreatments` t1
          INNER JOIN  `tbl_admissions` t2 ON t1.admission_id=t2.id
          INNER JOIN  `tbl_prescriptions` t9 ON t2.account_id=t9.visit_id
          INNER JOIN  `users` t3 ON t1.user_id=t3.id 
          INNER JOIN  `tbl_patients` t4 ON t1.patient_id=t4.id 
          INNER JOIN  `tbl_instructions` t5 ON t2.id=t5.admission_id 
          INNER JOIN  `tbl_items` t6 ON t6.id=t1.item_id 
          INNER JOIN  `tbl_beds` t7 ON t7.id=t5.bed_id 
          INNER JOIN  `tbl_wards` t8 ON t7.ward_id=t8.id 
          )";

    return DB::statement($sql);

}


	
	public static function getListOfTestsToVerify(){

		$patients="CREATE OR REPLACE VIEW  `vw_results_get_approves` AS(    
		SELECT t1.*,t2.sample_no,t4.item_name,t4.dept_id,
		CONCAT(t5.first_name,' ',t5.middle_name,' ',t5.last_name) AS full_name,
		t5.mobile_number,
		t2.sample_types,
		t2.clinical_note,
		DATE(t3.created_at) AS date_requested,
		t5.medical_record_number,
		(SELECT name FROM users t10 WHERE t10.id=t1.post_user GROUP BY t10.id) AS posted_by,
		CASE WHEN TIMESTAMPDIFF(YEAR,dob, CURRENT_DATE) <> 0 THEN CONCAT(TIMESTAMPDIFF(YEAR,dob, CURRENT_DATE), ' Years') ELSE CASE WHEN TIMESTAMPDIFF(MONTH,dob, CURRENT_DATE) <> 0 THEN CONCAT(TIMESTAMPDIFF(MONTH, dob, CURRENT_DATE), ' Months') ELSE CONCAT(TIMESTAMPDIFF(DAY, dob, CURRENT_DATE), ' Days') END END AS age,
		 CASE 
         WHEN t5.residence_id IS NOT NULL THEN (SELECT CONCAT(residence_name,' ',council_name) FROM tbl_residences t6 INNER JOIN tbl_councils t7 ON t6.council_id=t7.id WHERE t6.id=t5.residence_id GROUP BY t6.id) END AS residence_name,		 
		 CASE 
         WHEN t3.doctor_id IS NOT NULL THEN (SELECT name FROM users t8  WHERE t8.id=t3.doctor_id GROUP BY t8.name) END AS doctor_name,
		 CASE 
         WHEN t3.doctor_id IS NOT NULL THEN (SELECT t8.mobile_number  FROM users t8  WHERE t8.id=t3.doctor_id GROUP BY t8.mobile_number) END AS doctor_mobile_number	,
		 CASE 
         WHEN t3.requesting_department_id IS NOT NULL THEN (SELECT department_name  FROM tbl_departments t9  WHERE t3.requesting_department_id=t9.id GROUP BY t9.department_name) END AS requesting_department	,
		 CASE 
         WHEN t2.sample_no IS NOT NULL THEN (SELECT DATE(t10.created_at)  FROM tbl_sample_number_controls t10  
		 WHERE  TRIM(LEADING '0' FROM t10.sample_no)=t2.sample_no GROUP BY t10.created_at LIMIT 1) END AS date_collected,
		 CASE 
         WHEN t2.sample_no IS NOT NULL THEN (SELECT TIME(t10.created_at)  FROM tbl_sample_number_controls t10 
		 
		 WHERE  TRIM(LEADING '0' FROM t10.sample_no)=t2.sample_no GROUP BY t10.created_at LIMIT 1) END AS time_collected	 ,
		 CASE 
         WHEN t2.sample_no IS NOT NULL THEN (SELECT name  FROM users t11
         INNER JOIN  tbl_sample_number_controls t10  ON t10.user_id=t11.id
         WHERE t2.receiver_id=t10.user_id GROUP BY t2.receiver_id LIMIT 1) END AS collected_by
		 
		FROM tbl_results t1 
        INNER JOIN tbl_requests t3 ON t3.id = t1.order_id
        INNER JOIN tbl_orders t2 ON t1.item_id = t2.test_id
        INNER JOIN tbl_items t4 ON t4.id = t1.item_id
        INNER JOIN tbl_patients t5 ON t5.id = t3.patient_id
        WHERE t4.dept_id = 2
          AND t1.confirmation_status IS NULL 
          AND t2.order_id=t3.id 
          group by t1.item_id,t1.order_id)";
return DB::statement($patients);

	}
	
	public static function getSampleReports(){

		$patients="CREATE OR REPLACE VIEW  `vw_getSampleReports` AS(    
		SELECT t1.*,t2.sample_no,t4.item_name,t4.dept_id,
		CONCAT(t5.first_name,' ',t5.middle_name,' ',t5.last_name) AS full_name,
		t5.mobile_number,
		t2.sample_types,
		t2.clinical_note,
		DATE(t3.created_at) AS date_requested,
		t5.medical_record_number,
		(SELECT name FROM users t10 WHERE t10.id=t1.post_user GROUP BY t10.id) AS posted_by,
		CASE WHEN TIMESTAMPDIFF(YEAR,dob, CURRENT_DATE) <> 0 THEN CONCAT(TIMESTAMPDIFF(YEAR,dob, CURRENT_DATE), ' Years') ELSE CASE WHEN TIMESTAMPDIFF(MONTH,dob, CURRENT_DATE) <> 0 THEN CONCAT(TIMESTAMPDIFF(MONTH, dob, CURRENT_DATE), ' Months') ELSE CONCAT(TIMESTAMPDIFF(DAY, dob, CURRENT_DATE), ' Days') END END AS age,
		 CASE 
         WHEN t5.residence_id IS NOT NULL THEN (SELECT CONCAT(residence_name,' ',council_name) FROM tbl_residences t6 INNER JOIN tbl_councils t7 ON t6.council_id=t7.id WHERE t6.id=t5.residence_id GROUP BY t6.id) END AS residence_name,		 
		 CASE 
         WHEN t3.doctor_id IS NOT NULL THEN (SELECT name FROM users t8  WHERE t8.id=t3.doctor_id GROUP BY t8.name) END AS doctor_name,
		 CASE 
         WHEN t3.doctor_id IS NOT NULL THEN (SELECT t8.mobile_number  FROM users t8  WHERE t8.id=t3.doctor_id GROUP BY t8.mobile_number) END AS doctor_mobile_number	,
		 CASE 
         WHEN t3.requesting_department_id IS NOT NULL THEN (SELECT department_name  FROM tbl_departments t9  WHERE t3.requesting_department_id=t9.id GROUP BY t9.department_name) END AS requesting_department	,
		 CASE 
         WHEN t2.sample_no IS NOT NULL THEN (SELECT DATE(t10.created_at)  FROM tbl_sample_number_controls t10  
		 WHERE  TRIM(LEADING '0' FROM t10.sample_no)=t2.sample_no GROUP BY t10.created_at LIMIT 1) END AS date_collected,
		 CASE 
         WHEN t2.sample_no IS NOT NULL THEN (SELECT TIME(t10.created_at)  FROM tbl_sample_number_controls t10 
		 
		 WHERE  TRIM(LEADING '0' FROM t10.sample_no)=t2.sample_no GROUP BY t10.created_at LIMIT 1) END AS time_collected	 ,
		 CASE 
         WHEN t2.sample_no IS NOT NULL THEN (SELECT name  FROM users t11
         INNER JOIN  tbl_sample_number_controls t10  ON t10.user_id=t11.id
         WHERE t2.receiver_id=t10.user_id GROUP BY t2.receiver_id LIMIT 1) END AS collected_by
		 
		FROM tbl_results t1 
        INNER JOIN tbl_requests t3 ON t3.id = t1.order_id
        INNER JOIN tbl_orders t2 ON t1.item_id = t2.test_id
        INNER JOIN tbl_items t4 ON t4.id = t1.item_id
        INNER JOIN tbl_patients t5 ON t5.id = t3.patient_id
        WHERE t4.dept_id = 2
           AND t2.order_id=t3.id 
          group by t1.item_id,t1.order_id)";
return DB::statement($patients);

	}



//                  JAPHARI VIEWS
    public static function patientsDetails(){
        $patients="CREATE OR REPLACE VIEW  `vw_patient_details` AS
       SELECT 
        t2.id as patient_id,
        t2.first_name,
        t2.middle_name,
        t2.last_name,
        t2.medical_record_number,
        t2.dob,
         CASE WHEN TIMESTAMPDIFF(YEAR,dob, CURRENT_DATE) <> 0 THEN CONCAT(TIMESTAMPDIFF(YEAR,dob, CURRENT_DATE), ' Years') ELSE CASE WHEN TIMESTAMPDIFF(MONTH,dob, CURRENT_DATE) <> 0 THEN CONCAT(TIMESTAMPDIFF(MONTH, dob, CURRENT_DATE), ' Months') ELSE CONCAT(TIMESTAMPDIFF(DAY, dob, CURRENT_DATE), ' Days') END END
        AS age,
        t2.mobile_number,
        t3.council_name,
        (select t.name from users as t where t.id=t2.user_id) as registered_by,
         t1.residence_name,
        t4.region_name,
        t5.description,
        t6.facility_name,
        t7.marital_status,
        t8.occupation_name,
        t9.country_name
        FROM tbl_residences t1,tbl_patients t2,tbl_councils t3, tbl_regions t4, tbl_council_types t5, tbl_facilities t6, tbl_maritals t7,
        tbl_occupations t8, tbl_countries t9 
        WHERE 
            t1.id = t2.residence_id
            AND t3.id = t1.council_id
            AND t4.id = t3.regions_id
            AND t5.id = t3.council_types_id
            AND t6.id = t2.facility_id
            AND t7.id = t2.marital_id
            AND t8.id = t2.occupation_id
            AND t9.id = t2.country_id
         ";
        return DB::statement($patients);

    }

    public static function therapyTreatments()
    {
        $treatment = "CREATE OR REPLACE VIEW `vw_therapy_treatments` AS (SELECT 
        t1.id AS patient_id,
        t1.first_name,
        t1.middle_name,
        t1.last_name,
        t1.medical_record_number,
        t1.dob,
        t1.gender,
        t2.account_number,
        t2.created_at,
        t2.id AS account_id,
        t2.date_attended AS visit_date,
        t2.facility_id,
        t3.aim,
        t3.working,
        t3.created_at as date_investigated,
        t3.plans,
        t3.evaluation,
        t3.family
        FROM tbl_patients t1,tbl_accounts_numbers t2,tbl_therapy_treatments t3
       WHERE
            t1.id=t2.patient_id 
            AND t2.id=t3.visit_date_id
        )";
        return DB::statement($treatment);
    }

    public static function continuation_notes()
    {
        $treatment = "CREATE OR REPLACE VIEW `vw_continuation_notes` AS (SELECT 
        t1.id AS patient_id,
        t1.first_name,
        t1.middle_name,
        t1.last_name,
        t1.medical_record_number,
        t1.dob,
        t1.mobile_number,
        t1.gender,
        t2.account_number,
        t3.created_at,
        t2.id AS account_id,
        t2.date_attended AS visit_date,
        t2.facility_id,
        t3.refferal_id,
        t4.follow_up_status_description,
        t5.dept_id
    
        FROM tbl_patients t1,tbl_accounts_numbers t2, tbl_clinic_attendances t3, tbl_follow_up_statuses t4, tbl_clinic_instructions t5
       WHERE
            t1.id=t2.patient_id 
            AND t2.id=t3.visit_id
            AND t4.id=t3.follow_up_status
            AND t5.id=t3.refferal_id
           
        )";
        return DB::statement($treatment);
    }

    public static function therapy_assessments()
    {
        $treatment = "CREATE OR REPLACE VIEW `vw_therapy_assessments` AS (SELECT 
        t1.id AS patient_id,
        t1.first_name,
        t1.middle_name,
        t1.last_name,
        t1.medical_record_number,
        t1.dob,
        t1.gender,
        t2.account_number,
        t2.created_at,
        t2.id AS account_id,
        t2.date_attended AS visit_date,
        t2.facility_id,
        t3.general,
        t3.created_at as date_investigated,
        t3.specific as examination,
        t3.neurological,
        t3.summary
        FROM tbl_patients t1,tbl_accounts_numbers t2,tbl_therapy_assessments t3
       WHERE
            t1.id=t2.patient_id 
            AND t2.id=t3.visit_date_id
        )";
        return DB::statement($treatment);
    }

    public static function exempted_clinic()
    {
        $treatment = "CREATE OR REPLACE VIEW `vw_exempted_clinics` AS (SELECT 
        t1.id AS patient_id,
        t1.first_name,
        t1.middle_name,
        t1.last_name,
        t1.medical_record_number,
        t1.dob,
        t1.gender,
        t2.account_number,
        t2.facility_id,
        t2.id AS account_id,
        t2.date_attended AS visit_date,
        t3.id as refferal_id,
        t3.summary,
        t3.priority,
        t3.received,
        t3.consultation_id,
        t3.created_at,
        t3.dept_id,
        t3.on_off
        FROM tbl_patients t1,tbl_accounts_numbers t2, tbl_clinic_instructions t3
         WHERE (timestampdiff(hour,t3.created_at,CURRENT_TIMESTAMP))<= 24
       AND
            t1.id=t2.patient_id 
            AND t2.id=t3.visit_id
            AND t3.consultation_id IS NULL
        )";
        return DB::statement($treatment);
    }
    public static function clinicPayee(){
    $clinic="CREATE OR REPLACE VIEW  `vw_clinic_to_pay` AS
       SELECT 
        t1.id AS patient_id,
        t1.first_name,
        t1.middle_name,
        t1.last_name,
        t1.medical_record_number,
        t1.dob,
        CASE WHEN TIMESTAMPDIFF(YEAR,dob, CURRENT_DATE) <> 0 THEN CONCAT(TIMESTAMPDIFF(YEAR,dob, CURRENT_DATE), ' Years') ELSE CASE WHEN TIMESTAMPDIFF(MONTH,dob, CURRENT_DATE) <> 0 THEN CONCAT(TIMESTAMPDIFF(MONTH, dob, CURRENT_DATE), ' Months') ELSE CONCAT(TIMESTAMPDIFF(DAY, dob, CURRENT_DATE), ' Days') END END
AS umri,
        (TIMESTAMPDIFF(YEAR,t1.dob, CURRENT_DATE)) AS age,
        t1.gender,
        t2.account_number,
        t2.status,
        t2.id AS account_id,
        t2.date_attended AS visit_date,
        t3.status_id AS payment_status_id,
        t3.payment_filter,
        t4.main_category_id,t4.bill_id,
        t2.facility_id,
        t6.id as transfer_id,
        t6.summary,
        t6.priority,
        t6.dept_id,
        t6.received,
        t6.doctor_requesting_id
        FROM tbl_patients t1,tbl_accounts_numbers t2,tbl_invoice_lines t3,tbl_bills_categories t4,tbl_encounter_invoices t5, tbl_clinic_instructions t6
        WHERE (timestampdiff(hour,t2.created_at,CURRENT_TIMESTAMP))<= 24
       AND t1.id = t2.patient_id
        AND t1.id = t3.patient_id
        AND t2.id = t4.account_id
        AND t2.id = t5.account_number_id
        AND t5.id = t3.invoice_id
        AND t2.id = t6.visit_id
         ";
    return DB::statement($clinic);

}
    public static function clinic_attendency_payee(){
        $attendence="CREATE OR REPLACE VIEW  `vw_attendance_payee` AS
       SELECT 
        t1.id AS patient_id,
        t1.first_name,
        t1.middle_name,
        t1.last_name,
        t1.medical_record_number,
        t1.dob,
        CASE WHEN TIMESTAMPDIFF(YEAR,dob, CURRENT_DATE) <> 0 THEN CONCAT(TIMESTAMPDIFF(YEAR,dob, CURRENT_DATE), ' Years') ELSE CASE WHEN TIMESTAMPDIFF(MONTH,dob, CURRENT_DATE) <> 0 THEN CONCAT(TIMESTAMPDIFF(MONTH, dob, CURRENT_DATE), ' Months') ELSE CONCAT(TIMESTAMPDIFF(DAY, dob, CURRENT_DATE), ' Days') END END
AS umri,
        (TIMESTAMPDIFF(YEAR,t1.dob, CURRENT_DATE)) AS age,
        t1.gender,
        t2.account_number,
        t2.status,
        t2.id AS account_id,
        t2.date_attended AS visit_date,
        t3.status_id AS payment_status_id,
        t3.payment_filter,
        t4.main_category_id,t4.bill_id,
        t2.facility_id,
        t6.id as transfer_id,
        t6.summary,
        t6.priority,
        t6.dept_id,
        t6.received,
        t6.doctor_requesting_id,
        t7.next_visit,
        t7.follow_up_status,
        t7.clinic_capacity
        FROM tbl_patients t1,tbl_accounts_numbers t2,tbl_invoice_lines t3,tbl_bills_categories t4,tbl_encounter_invoices t5, tbl_clinic_instructions t6, tbl_clinic_attendances t7
        WHERE (timestampdiff(hour,t2.created_at,CURRENT_TIMESTAMP))<= 24
       AND t1.id = t2.patient_id
        AND t1.id = t3.patient_id
        AND t2.id = t4.account_id
        AND t2.id = t5.account_number_id
        AND t5.id = t3.invoice_id
        AND t2.id = t6.visit_id
         AND t6.id = t7.refferal_id
         ";
        return DB::statement($attendence);

    }
    public static function emergency_records(){
        $emergency_records="CREATE OR REPLACE VIEW  `vw_emergency_records` AS
       SELECT 
        t3.first_name,
        t3.middle_name,
        t3.last_name,
        t3.dob,
        CASE WHEN TIMESTAMPDIFF(YEAR,dob, CURRENT_DATE) <> 0 THEN CONCAT(TIMESTAMPDIFF(YEAR,dob, CURRENT_DATE), ' Years') ELSE CASE WHEN TIMESTAMPDIFF(MONTH,dob, CURRENT_DATE) <> 0 THEN CONCAT(TIMESTAMPDIFF(MONTH, dob, CURRENT_DATE), ' Months') ELSE CONCAT(TIMESTAMPDIFF(DAY, dob, CURRENT_DATE), ' Days') END END
        AS age,
        t2.date_attended,
        t3.gender,
        t3.medical_record_number,
        t3.mobile_number,
        t4.emergency_type,
        t4.emergency_name,
        t1.created_at,
        t1.updated_at
        FROM tbl_emergency_patients t1, tbl_accounts_numbers t2, tbl_patients t3,tbl_emergency_types t4
        WHERE 
            t2.id = t1.visiting_id
            AND t3.id = t2.patient_id
            AND t4.id = t1.emergency_type_id
         ";
        return DB::statement($emergency_records);

    }
    public static function radiologyPatients(){
        $radiology="CREATE OR REPLACE VIEW `Vw_xray_orders` AS
SELECT 
        t1.patient_id,
        t1.visit_date_id, 
        t2.id AS request_id,
        t2.order_status,
        t5.date_attended,
        t3.id AS item_id,
        t5.account_number,
        t6.first_name,
        t6.middle_name,
        t6.last_name,
        t6.gender,
        t6.dob,
        t19.name as doctor_name,
        CASE WHEN TIMESTAMPDIFF(YEAR,dob, CURRENT_DATE) <> 0 THEN CONCAT(TIMESTAMPDIFF(YEAR,dob, CURRENT_DATE), ' Years') ELSE CASE WHEN TIMESTAMPDIFF(MONTH,dob, CURRENT_DATE) <> 0 THEN CONCAT(TIMESTAMPDIFF(MONTH, dob, CURRENT_DATE), ' Months') ELSE CONCAT(TIMESTAMPDIFF(DAY, dob, CURRENT_DATE), ' Days') END END
AS age,
        t6.medical_record_number,
        t6.mobile_number,
        t7.payment_filter,
        t7.status_id AS payment_status,
        t3.item_name,
        t18.sub_department_name,
        t18.id AS sub_department_id,
        t2.priority,
        t2.clinical_note,
        t1.admission_id,
        t1.id AS OrderId,
        CASE 
         WHEN t1.admission_id is NULL THEN 'OPD'  ELSE 'IPD'  END as dept,        
        t2.created_at,
        t4.facility_id
        
         FROM tbl_orders  t2
            INNER JOIN tbl_requests t1 ON t1.id=t2.order_id
            INNER JOIN tbl_accounts_numbers t5 ON t1.visit_date_id =t5.id
            INNER JOIN tbl_items t3 ON t3.id = t2.test_id
            INNER JOIN users t4 ON t1.doctor_id = t4.id
            INNER JOIN tbl_patients t6 ON t1.patient_id = t6.id
            INNER JOIN tbl_encounter_invoices t8 ON t5.id = t8.account_number_id
            INNER JOIN tbl_invoice_lines t7 ON t7.invoice_id = t8.id
            INNER JOIN tbl_pay_cat_sub_categories t14 ON t14.id= t7.payment_filter
            INNER JOIN tbl_payments_categories t15 ON t14.pay_cat_id= t15.id
            INNER JOIN tbl_item_prices t13 ON t2.test_id = t13.item_id           
            INNER JOIN tbl_sub_departments t18 ON t18.department_id = t3.dept_id          
            INNER JOIN users t19 ON t19.id = t1.doctor_id          
                   
           WHERE  
        t7.item_price_id = t13.id
        AND t2.test_id = t13.item_id
        AND t3.dept_id = 3
        AND (t7.status_id =2 OR t15.id >1) 
        AND (timestampdiff(hour,t5.created_at,CURRENT_TIMESTAMP))<= 48";


        return DB::statement($radiology);

    }
    public static function equipmentStatuses(){

        $status="CREATE OR REPLACE VIEW  `vw_equipment_status` AS
       SELECT tbl_items.item_name, 
        tbl_items.dept_id, 
        tbl_sub_departments.sub_department_name, 
        tbl_equipments.equipment_name,  
        tbl_equipments.facility_id, 
        tbl_equipments.equipment_status_id, 
        tbl_equipments.eraser as deleted, 
        tbl_equipment_statuses.status_name, 
        tbl_equipment_statuses.on_off,
        tbl_tests.item_id,
        tbl_tests.id as testID,
        tbl_tests.eraser as service_Deleted
        FROM tbl_equipments INNER JOIN tbl_tests on tbl_tests.equipment_id=tbl_equipments.id 
        INNER JOIN tbl_items ON tbl_items.id=tbl_tests.item_id 
        INNER JOIN tbl_sub_departments ON tbl_sub_departments.id=tbl_tests.sub_department_id
         INNER JOIN tbl_equipment_statuses ON tbl_equipment_statuses.id=tbl_equipments.equipment_status_id WHERE tbl_equipments.eraser=1";


        return DB::statement($status);

    }
    public static function patient_xray(){

        $xray_queue="CREATE OR REPLACE VIEW  `vw_xray_que` AS
       SELECT                   
                    tbl_invoice_lines.id,
                    tbl_encounter_invoices.account_number_id,                   
                    tbl_invoice_lines.invoice_id,
                    tbl_invoice_lines.patient_id,
                    tbl_patients.medical_record_number,
                    tbl_patients.dob,
        CASE WHEN TIMESTAMPDIFF(YEAR,dob, CURRENT_DATE) <> 0 THEN CONCAT(TIMESTAMPDIFF(YEAR,dob, CURRENT_DATE), ' Years') ELSE CASE WHEN TIMESTAMPDIFF(MONTH,dob, CURRENT_DATE) <> 0 THEN CONCAT(TIMESTAMPDIFF(MONTH, dob, CURRENT_DATE), ' Months') ELSE CONCAT(TIMESTAMPDIFF(DAY, dob, CURRENT_DATE), ' Days') END END
AS age,
        (TIMESTAMPDIFF(YEAR,tbl_patients.dob, CURRENT_DATE)) AS umri,
                    tbl_patients.gender,
                    tbl_patients.first_name,
                    tbl_patients.middle_name,
                    tbl_patients.last_name,
                    tbl_patients.mobile_number,                 
                    tbl_invoice_lines.status_id, 
                    tbl_invoice_lines.facility_id,
                    tbl_invoice_lines.id AS item_refference,
                    tbl_encounter_invoices.id AS receipt_number,
                    tbl_invoice_lines.item_type_id,
                    tbl_invoice_lines.payment_filter,
                    tbl_payments_categories.id AS main_category_id,
                    tbl_invoice_lines.created_at,
                    tbl_item_prices.price,
                    tbl_item_prices.price AS unit_price,
                    tbl_items.item_name, 
                    tbl_items.dept_id,
                    tbl_pay_cat_sub_categories.sub_category_name,
                    tbl_payments_categories.category_description,
                    tbl_accounts_numbers.account_number,
                    tbl_accounts_numbers.created_at as visited_date,
                    tbl_accounts_numbers.date_attended,
                    tbl_payment_statuses.payment_status 
    FROM tbl_invoice_lines INNER JOIN tbl_item_type_mappeds ON tbl_invoice_lines.item_type_id = tbl_item_type_mappeds.id
    INNER JOIN tbl_item_prices ON tbl_invoice_lines.item_price_id = tbl_item_prices.ID
    INNER JOIN tbl_items ON tbl_item_prices.item_id = tbl_items.id 
    INNER JOIN tbl_payment_statuses ON tbl_invoice_lines.status_id = tbl_payment_statuses.ID 
    INNER JOIN tbl_encounter_invoices ON tbl_invoice_lines.invoice_id = tbl_encounter_invoices.id 
    INNER JOIN tbl_accounts_numbers ON tbl_encounter_invoices.account_number_id = tbl_accounts_numbers.ID 
    INNER JOIN tbl_patients ON tbl_accounts_numbers.patient_id = tbl_patients.id                    
    INNER JOIN tbl_pay_cat_sub_categories ON tbl_invoice_lines.payment_filter = tbl_pay_cat_sub_categories.id                   
    INNER JOIN tbl_payments_categories ON tbl_pay_cat_sub_categories.pay_cat_id = tbl_payments_categories.id 
    WHERE (timestampdiff(hour,tbl_accounts_numbers.created_at,CURRENT_TIMESTAMP))<= 24
                        AND tbl_items.dept_id = 3  
";
        return DB::statement($xray_queue);

    }

    public static function postedResults(){

        $posted="CREATE OR REPLACE VIEW  `vw_postedResults` AS
        SELECT
t5.id as patient_id,
t5.first_name,
t5.middle_name,
t5.last_name,
t5.medical_record_number,
t5.mobile_number,
t5.dob,
 CASE WHEN TIMESTAMPDIFF(YEAR,dob, CURRENT_DATE) <> 0 THEN CONCAT(TIMESTAMPDIFF(YEAR,dob, CURRENT_DATE), ' Years') ELSE CASE WHEN TIMESTAMPDIFF(MONTH,dob, CURRENT_DATE) <> 0 THEN CONCAT(TIMESTAMPDIFF(MONTH, dob, CURRENT_DATE), ' Months') ELSE CONCAT(TIMESTAMPDIFF(DAY, dob, CURRENT_DATE), ' Days') END END
AS Ages,
        (TIMESTAMPDIFF(YEAR,t5.dob, CURRENT_DATE)) AS age,
t1.order_id,
t1.description,
t1.created_at,
t1.eraser,
t1.post_time,
t1.attached_image,
t1.remarks,
t2.visit_date_id,
t3.priority,
t3.clinical_note,
t4.item_name,
t4.dept_id

    FROM 
    tbl_results t1, tbl_requests t2, tbl_orders t3, tbl_items t4, tbl_patients t5
    WHERE 
    t2.id = t1.order_id
    AND t3.order_id = t2.id
    AND t3.test_id = t4.id
    AND t2.patient_id = t5.id
    AND dept_id = 3
         
         ";


        return DB::statement($posted);

    }

    public static function vitalSign(){
        $vitalSign = "CREATE OR REPLACE VIEW `vw_vital_sign_users` AS (SELECT 
        t1.id AS patient_id,
        t1.first_name,
        t1.middle_name,
        t1.last_name,
        t1.medical_record_number,
        t1.dob,
        CASE WHEN TIMESTAMPDIFF(YEAR,dob, CURRENT_DATE) <> 0 THEN CONCAT(TIMESTAMPDIFF(YEAR,dob, CURRENT_DATE), ' Years') ELSE CASE WHEN TIMESTAMPDIFF(MONTH,dob, CURRENT_DATE) <> 0 THEN CONCAT(TIMESTAMPDIFF(MONTH, dob, CURRENT_DATE), ' Months') ELSE CONCAT(TIMESTAMPDIFF(DAY, dob, CURRENT_DATE), ' Days') END END
AS age,
        (TIMESTAMPDIFF(YEAR,t1.dob, CURRENT_DATE)) AS umri,
        t1.gender,
        t2.account_number,
        t2.status,
        t2.id AS account_id,
        t2.date_attended AS visit_date,
        t3.status_id AS payment_status_id,
        t3.payment_filter,
        t4.main_category_id,t4.bill_id,
        t2.facility_id
        FROM tbl_patients t1,tbl_accounts_numbers t2,tbl_invoice_lines t3,tbl_bills_categories t4,tbl_encounter_invoices t5
        WHERE t1.id = t2.patient_id
        AND t1.id = t3.patient_id
        AND t2.id = t4.account_id
        AND t2.id = t5.account_number_id
        AND t5.id = t3.invoice_id)";
        return DB::statement($vitalSign);
    }


    public static function vitalSignOutput(){
        $vitalSignOutput = "CREATE OR REPLACE VIEW `vw_vital_sign_output` AS (SELECT 
        t1.id AS patient_id,
        t1.first_name,
        t1.middle_name,
        t1.last_name,
        t1.medical_record_number,
        t1.dob,
        CASE WHEN TIMESTAMPDIFF(YEAR,dob, CURRENT_DATE) <> 0 THEN CONCAT(TIMESTAMPDIFF(YEAR,dob, CURRENT_DATE), ' Years') ELSE CASE WHEN TIMESTAMPDIFF(MONTH,dob, CURRENT_DATE) <> 0 THEN CONCAT(TIMESTAMPDIFF(MONTH, dob, CURRENT_DATE), ' Months') ELSE CONCAT(TIMESTAMPDIFF(DAY, dob, CURRENT_DATE), ' Days') END END
AS age,
       t4.vital_name,
       t4.si_unit,
       
 (select t.name from users as t where t.id=t3.registered_by) as submited_by,
        t1.gender,
        t2.account_number,
        t2.status,
        t2.id AS account_id,
        t2.date_attended AS visit_date,
        t3.visiting_id,
        t3.vital_sign_value,
        t3.registered_by,
        t3.date_taken,
        t3.time_taken,
        t3.created_at
      
       
        FROM tbl_patients t1,tbl_accounts_numbers t2, tbl_vital_signs t3, tbl_vitals t4
        WHERE 
            t1.id = t2.patient_id
            AND t4.id = t3.vital_sign_id
        AND t2.id = t3.visiting_id
        )";
        return DB::statement($vitalSignOutput);
    }

    public static function imaging_rejesta(){

        $rejesta="CREATE OR REPLACE VIEW  `vw_imaging_rejesta` AS
       
     SELECT                     
                    tbl_invoice_lines.id,
                    tbl_invoice_lines.id AS item_refference,
                    tbl_encounter_invoices.id AS receipt_number,                    
                    tbl_invoice_lines.invoice_id,
                    tbl_invoice_lines.patient_id,
                    tbl_invoice_lines.status_id,
                    tbl_patients.medical_record_number,
                    tbl_patients.dob,
                      CASE WHEN TIMESTAMPDIFF(YEAR,dob, CURRENT_DATE) <> 0 THEN CONCAT(TIMESTAMPDIFF(YEAR,dob, CURRENT_DATE), ' Years') ELSE CASE WHEN TIMESTAMPDIFF(MONTH,dob, CURRENT_DATE) <> 0 THEN CONCAT(TIMESTAMPDIFF(MONTH, dob, CURRENT_DATE), ' Months') ELSE CONCAT(TIMESTAMPDIFF(DAY, dob, CURRENT_DATE), ' Days') END END
AS age,
                    tbl_patients.gender,
                    tbl_patients.first_name,
                    tbl_patients.middle_name,
                    tbl_patients.last_name,
                    tbl_patients.mobile_number,                 
                    tbl_invoice_lines.quantity, 
                    tbl_invoice_lines.discount, 
                    tbl_invoice_lines.facility_id,
                    tbl_invoice_lines.discount_by,
                    tbl_invoice_lines.payment_filter,
                    tbl_invoice_lines.created_at,
                    tbl_item_prices.price,
                    tbl_items.item_name, 
                    tbl_items.dept_id,
                    users.id as user_id,
                    users.name as user_name,
                    tbl_item_type_mappeds.item_category,
                     tbl_item_type_mappeds.sub_item_category,
                    tbl_accounts_numbers.account_number, tbl_payment_statuses.payment_status 
    FROM tbl_invoice_lines INNER JOIN tbl_item_type_mappeds ON tbl_invoice_lines.item_type_id = tbl_item_type_mappeds.id 
    INNER JOIN tbl_item_prices ON tbl_invoice_lines.item_price_id = tbl_item_prices.ID
    INNER JOIN tbl_items ON tbl_item_prices.item_id = tbl_items.id 
    INNER JOIN tbl_payment_statuses ON tbl_invoice_lines.status_id = tbl_payment_statuses.ID 
    INNER JOIN tbl_encounter_invoices ON tbl_invoice_lines.invoice_id = tbl_encounter_invoices.id       
    INNER JOIN tbl_accounts_numbers ON tbl_encounter_invoices.account_number_id = tbl_accounts_numbers.ID 
    INNER JOIN tbl_patients ON tbl_accounts_numbers.patient_id = tbl_patients.id 
    INNER JOIN users ON tbl_invoice_lines.user_id = users.id
    WHERE tbl_items.dept_id = 3
    
    ";

        return DB::statement($rejesta);

    }
//@END JAPHARI VIEWS-RADIOLOGY

   public static function pendingDischarge(){

        $conf_admission= "CREATE OR REPLACE VIEW `vw_pending_discharge` AS (
        
	SELECT  t2.patient_id,
		t1.medical_record_number,
		t1.mobile_number,
        t2.admission_status_id,
		t4.admission_id,
		t4.ward_id,
		t6.nurse_id,
		t4.bed_id,
		t5.ward_name,
		t4.instructions,
		t4.prescriptions,
		t2.admission_date,	
		(SELECT residence_name FROM tbl_residences t1 INNER JOIN tbl_patients t2 ON t2.residence_id=t1.id  GROUP BY t1.residence_id) AS residence_name,
		
		(SELECT council_name 
		FROM tbl_residences t1 
		INNER JOIN tbl_patients t2 ON t2.residence_id=t1.id 
		INNER JOIN tbl_councils t3 ON t3.id=t1.council_id 
		GROUP BY t1.council_id) AS council_name,
		
        t9.name,
		CASE 
	    WHEN t2.account_id IS NOT NULL THEN (SELECT t12.main_category_id FROM tbl_bills_categories t12 WHERE t12.account_id=t2.account_id GROUP BY t2.account_id
	    ) END AS main_category_id,		
	    CASE 
	    WHEN t2.account_id IS NOT NULL THEN (SELECT t12.bill_id FROM tbl_bills_categories t12 WHERE t12.account_id=t2.account_id GROUP BY t2.account_id
	    ) END AS patient_category_id,	
		t9.mobile_number AS doctor_mob,		
        t4.updated_at,		
        t4.created_at,		
		CONCAT(t1.first_name,' ',t1.middle_name,' ',t1.last_name) AS fullname,
		t2.facility_id 
        FROM tbl_admissions t2
        INNER JOIN tbl_instructions t4 ON t4.admission_id=t2.id
        INNER JOIN tbl_wards t5 ON t5.id =t4.ward_id
        INNER JOIN tbl_nurse_wards t6 ON t6.ward_id=t4.ward_id
        INNER JOIN tbl_patients t1 ON t1.id = t2.patient_id
        INNER JOIN users t9 ON t2.user_id=t9.id  
        WHERE t2.admission_status_id=3 AND t6.deleted=0
        )";
        return DB::statement($conf_admission);
          }

         
public  static function getPrescribedItems(){
    $sql="CREATE OR REPLACE VIEW `vw_prescribed_items` AS (SELECT t2.id AS admission_id,t7.bed_name,t8.ward_name,t6.item_name,t1.patient_id,t2.admission_date,
                t1.item_id,t4.medical_record_number,CONCAT(t4.first_name,' ',t4.middle_name,' ',t4.last_name) AS patient_name,t5.bed_id,t5.ward_id,
                t2.facility_id ,t3.name AS dr_ordered,t1.quantity,t1.frequency,t1.duration,t1.dose,t1.start_date,t1.instruction
          FROM `tbl_prescriptions` t1
          INNER JOIN  `tbl_admissions` t2 ON t1.visit_id=t2.account_id
          INNER JOIN  `users` t3 ON t1.prescriber_id=t3.id 
          INNER JOIN  `tbl_patients` t4 ON t1.patient_id=t4.id 
          INNER JOIN  `tbl_instructions` t5 ON t2.id=t5.admission_id 
          INNER JOIN  `tbl_items` t6 ON t6.id=t1.item_id 
          INNER JOIN  `tbl_beds` t7 ON t7.id=t5.bed_id 
          INNER JOIN  `tbl_wards` t8 ON t7.ward_id=t8.id 
          WHERE t1.dispensing_status IS NOT NULL)";

    return DB::statement($sql);

}
  public static function pendingAdmission(){
				 //admission_status_id=1 repressent pending admission..
        $pending_admission= "CREATE OR REPLACE VIEW `vw_pending_admission` AS (
		SELECT  t2.patient_id,
		t1.medical_record_number,
		t1.mobile_number,
		t4.admission_id,
		t4.ward_id,
		t9.name,
		t9.mobile_number AS doctor_mob,
		t6.nurse_id,
		t5.ward_name,
		t4.instructions,
		t4.prescriptions,
		t2.admission_date,	
		t2.account_id AS visit_date_id,	
		(SELECT residence_name FROM tbl_residences t1 INNER JOIN tbl_patients t2 ON t2.residence_id=t1.id  GROUP BY t1.residence_id LIMIT 1) AS residence_name,
		
		(SELECT council_name 
		FROM tbl_residences t1 
		INNER JOIN tbl_patients t2 ON t2.residence_id=t1.id 
		INNER JOIN tbl_councils t3 ON t3.id=t1.council_id 
		GROUP BY t1.council_id LIMIT 1) AS council_name,
		
		t4.updated_at,		
        t4.created_at,		
		CONCAT(t1.first_name,' ',t1.middle_name,' ',t1.last_name) AS fullname,
		t2.facility_id 
        FROM tbl_admissions t2
		INNER JOIN tbl_instructions t4 ON t4.admission_id=t2.id
        INNER JOIN tbl_wards t5 ON t5.id =t4.ward_id
        INNER JOIN tbl_nurse_wards t6 ON t6.ward_id=t4.ward_id
        INNER JOIN tbl_patients t1 ON t1.id = t2.patient_id
        INNER JOIN users t9 ON t2.user_id=t9.id          
        WHERE t2.admission_status_id=1 AND t6.deleted=0)";
		return DB::statement($pending_admission);		
          }
		  
		  
		  public static function approvedAdmission(){

        $conf_admission= "CREATE OR REPLACE VIEW `vw_approved_admission` AS (
        
	SELECT  t2.patient_id,
		t1.medical_record_number,
		t1.mobile_number,
        t2.admission_status_id,
		t4.admission_id,
		t4.ward_id,
		t6.nurse_id,
		t6.deleted,
		t4.bed_id,
		t5.ward_name,
		t4.instructions,
		t4.prescriptions,
		t2.admission_date,	
		(SELECT residence_name FROM tbl_residences t1 INNER JOIN tbl_patients t2 ON t2.residence_id=t1.id  GROUP BY t1.residence_id LIMIT 1) AS residence_name,
		
		(SELECT council_name 
		FROM tbl_residences t1 
		INNER JOIN tbl_patients t2 ON t2.residence_id=t1.id 
		INNER JOIN tbl_councils t3 ON t3.id=t1.council_id 
		GROUP BY t1.council_id LIMIT 1) AS council_name,
        t10.bed_name,
        t9.name,
        t2.account_id AS visit_date_id,
			
	CASE 
	WHEN t2.account_id IS NOT NULL THEN (SELECT t12.main_category_id FROM tbl_bills_categories t12 WHERE t12.account_id=t2.account_id GROUP BY t2.account_id 
	LIMIT 1) END AS main_category_id,		
	
	CASE 
	WHEN t2.account_id IS NOT NULL THEN (SELECT t12.bill_id FROM tbl_bills_categories t12 WHERE t12.account_id=t2.account_id GROUP BY t2.account_id 
	 LIMIT 1) END AS patient_category_id,
	
	CASE 
	WHEN t2.account_id IS NOT NULL THEN (SELECT t12.date_attended FROM tbl_accounts_numbers t12 WHERE t12.id=t2.account_id LIMIT 1
	) END AS date_attended,
	
        t1.gender,
		t9.mobile_number AS doctor_mob,		
        t4.updated_at,		
        t4.created_at,		
		CONCAT(t1.first_name,' ',t1.middle_name,' ',t1.last_name) AS fullname,
		t2.facility_id 
		
		
        FROM tbl_admissions t2
        INNER JOIN tbl_instructions t4 ON t4.admission_id=t2.id
        INNER JOIN tbl_beds t10 ON t10.id=t4.bed_id
        INNER JOIN tbl_wards t5 ON t5.id =t4.ward_id
        INNER JOIN tbl_nurse_wards t6 ON t6.ward_id=t4.ward_id
        INNER JOIN tbl_patients t1 ON t1.id = t2.patient_id
        INNER JOIN users t9 ON t2.user_id=t9.id  
        WHERE t2.admission_status_id=2 AND t6.deleted=0
        )";
        return DB::statement($conf_admission);
          }

        public static function getDischargedPatients(){
		$getDischargedPatients="CREATE OR REPLACE VIEW `vw_discharged_lists` AS (
		SELECT  t2.patient_id,
		t1.medical_record_number,
		t1.mobile_number,
		t4.admission_id,
		t4.ward_id,
		t4 .bed_id,
		t4.updated_at,		
		t5.updated_at AS time_discharged,		
		t5.id AS discharge_id,		
		t5.nurse_id AS discharged_by,		
		t2.admission_date,		
		CONCAT(t1.first_name,' ',t1.middle_name,' ',t1.last_name) AS fullname,
		t2.facility_id 
		FROM tbl_discharge_permits t5 INNER JOIN tbl_admissions t2 ON t5.admission_id=t2.id 
	    INNER JOIN tbl_patients t1 ON t1.id =t2.patient_id
	    INNER JOIN vw_residences t3 ON t3.residence_id=t1.residence_id 
	    INNER JOIN tbl_instructions t4 ON t4.admission_id=t2.id 	
	    WHERE t5.confirm=1)";
		return DB::statement($getDischargedPatients);
        }


        public static function getCabinets(){
		$cabinet_lists="CREATE OR REPLACE VIEW `vw_cabinet_lists` AS (
		SELECT  t2.cabinet_name,
		t2.capacity,
		t2.id,
		t1.id AS mortuary_id,
		t2.id AS cabinet_id,
		CASE WHEN 
		t2.id IS NOT NULL THEN (SELECT count(*) AS corpses_number FROM tbl_corpse_admissions t3  WHERE t3.cabinet_id=t2.id GROUP BY t2.id) ELSE 0 END AS corpses_number, 
		t1.mortuary_name,
		t1.facility_id
		FROM  tbl_mortuaries t1 INNER JOIN tbl_cabinets t2 ON t1.id=t2.mortuary_id 
	    )";
		return DB::statement($cabinet_lists);
        }



		public static function createWardDetails(){
	    $createWardDetails= "CREATE OR REPLACE VIEW `vw_wards` AS (
		SELECT  t1.id AS ward_id,
		t1.ward_name,
		t2.ward_type_name,		
		CONCAT(t1.ward_name,' ',t2.ward_type_name) AS ward_full_name,
		t1.facility_id 
        FROM tbl_wards t1, 
        tbl_wards_types t2 		
		WHERE t1.ward_type_id =t2.id) ";
		return DB::statement($createWardDetails);		
          }
		
		
		public static function createBedsDetails(){
				 //admission_status_id=2 repressent confirmed admission..
        $createBedsDetails= "CREATE OR REPLACE VIEW `vw_beds` AS (
		SELECT  t2.id AS bed_id,
		t1.ward_id,
		t1.ward_full_name,
		CONCAT(t2.bed_name,' ',t3.bed_type) AS bed_available,
		t3.bed_type,
		t2.occupied,
		t1.facility_id 
        FROM
		vw_wards t1, 
        tbl_beds t2,
        tbl_bed_types t3 		
		WHERE t1.ward_id =t2.ward_id AND t2.bed_type_id=t3.id) ";
		return DB::statement($createBedsDetails);		
          }



			public static function createCabinetsDetails(){
				 //admission_status_id=2 repressent confirmed admission..
        $createCabinetsDetails= "CREATE OR REPLACE VIEW `vw_cabinets` AS (
		SELECT  t2.id AS cabinet_id,
		t1.id AS mortuary_id,
		t1.mortuary_name,
		t2.cabinet_name,
		t2.occupied,
		t1.facility_id 		
        FROM    tbl_mortuaries t1	        
                 INNER JOIN tbl_cabinets t2 ON t2.mortuary_id=t1.id)";
		return DB::statement($createCabinetsDetails);
          }

			 public static function searchpatient()
    {
$search_query= "CREATE OR REPLACE VIEW `vw_patients_search` AS (SELECT t1.id, t1.id AS patient_id,t1.residence_id,t1.gender,t1.first_name,t1.middle_name,t1.last_name,t1.medical_record_number,t1.mobile_number,CONCAT(t1.first_name,' ',t1.middle_name,' ',t1.last_name) AS fullname,t2.membership_number,t2.account_number, t2.facility_id  FROM tbl_patients t1, tbl_accounts_numbers t2 WHERE t1.id = t2.patient_id
      
        )";
		//return dd($search_query);
		return DB::statement($search_query);
		
    }
/**
		
		 public static function searchpatient()
    {
$search_query= "CREATE OR REPLACE VIEW `vw_patients_search` AS (SELECT t1.id, t1.id AS patient_id,t1.first_name,t1.middle_name,t1.last_name,t1.medical_record_number,t1.mobile_number,CONCAT(t1.first_name,' ',t1.middle_name,' ',t1.last_name) AS fullname,t2.account_number, t2.facility_id  FROM tbl_patients t1, tbl_accounts_numbers t2 WHERE t1.id = t2.patient_id
      
        )";
		//return dd($search_query);
		return DB::statement($search_query);
		
    }
**/

	public static function createUserDetails(){
					 $login_user_details="CREATE OR REPLACE VIEW `vw_user_details` AS (SELECT 
                     t1.id as user_id,
					 t1.name,
					 t1.email,
					 t1.mobile_number,
				     t1.gender,
					 t1.user_type,
				     t2.prof_name
					
					 FROM users t1
      INNER JOIN  tbl_proffesionals t2 ON t2.id=t1.user_type)";	
		
					 return DB::statement($login_user_details);
					}
									
					
	public static function createResidences(){
					 $vw_residences="CREATE OR REPLACE VIEW `vw_residences` AS ( SELECT 
				     t1.id as residence_id,
					 t1.council_id,
					 CONCAT(t1.residence_name,' ',t2.council_name) AS residence_name,
					 t3.region_name
					 FROM  tbl_residences t1, 
					 tbl_councils t2,
					 tbl_regions t3
					 WHERE t2.id=t1.council_id 
					 AND t3.id=t2.regions_id)";	
		
					 return DB::statement($vw_residences);
					}



	public static function accessLevel(){
				    $vw_user_level="CREATE OR REPLACE VIEW `vw_user_level` AS (SELECT 
					t4.module as state_p, 
					t5.grant as allowed,
					t4.title as descr,
					t4.id as permission_id,
					t4.glyphicons as icons,
					t1.title as user_type 
					FROM tbl_permission_roles t5, 
					tbl_roles t1 , 
					tbl_permissions t4 
					WHERE  t5.role_id=t1.id 
					AND t5.permission_id=t4.id )";	
		
					 return DB::statement($vw_user_level);
					}
					
	public static function registrarServices(){
				    $registrarServices="CREATE OR REPLACE VIEW `vw_registrar_services` AS( SELECT
					t1.service_id,
					t1.facility_id,       
					t2.item_name,
					t2.patient_category,
					t2.patient_category_id,
					t2.patient_main_category_id,
					t2.price_id,
					t2.item_type_id,
					t2.price 
					FROM  tbl_registrar_services t1,
					vw_shop_items t2            
					WHERE 
					t1.service_id = t2.item_id       
					)";	
		
					 return DB::statement($registrarServices);
					}
					
	public static function residencesView(){
				    $residencesView="CREATE OR REPLACE VIEW `vw_residences` AS ( select
					t1.id AS residence_id, 
					t1.residence_name, 
					t2.council_name,
					t4.region_name				 
					FROM tbl_residences t1, 
					tbl_councils t2 , 
					tbl_council_types t3,
					tbl_regions t4	 
					WHERE  t1.council_id=t2.id 
					AND    t4.id=t2.regions_id  
					AND    t2.council_types_id=t3.id)";	
		
					 return DB::statement($residencesView);
					}
					
	public static function useraccessLevel(){
		   	$vw_user_access_level="CREATE OR REPLACE VIEW `vw_user_access_level` AS (SELECT
					t4.module as state_p, 
					t6.grant as allowed,
					t4.title as descr,
					t4.id as permission_id,
					t4.glyphicons as icons,
					t6.user_id,					
					t6.grant as is_it_allowed_to_access,					
					t4.title as user_type 
		    FROM tbl_permissions t4
			INNER JOIN tbl_permission_users t6 ON t6.permission_id=t4.id)";
							
			 return DB::statement($vw_user_access_level);
					
					}
					
					
					
			//start new view		
			
	public static function billsPayments(){
		            $vw_bills_payments="CREATE OR REPLACE VIEW vw_bills_payments AS (

SELECT t1.*,
       t2.item_category,t3.price,
	   t3.price AS unit_price,
	   t1.id AS item_refference,
	   t6.id AS receipt_number,
       
	   CASE WHEN t1.patient_id IS NOT NULL THEN (SELECT t9.first_name FROM tbl_patients t9 WHERE t9.id=t1.patient_id  GROUP BY t1.patient_id) ELSE (SELECT t10.first_name FROM tbl_corpses t10 WHERE t10.id=t1.corpse_id  GROUP BY t1.corpse_id) END AS first_name,   
	   
	   CASE WHEN t1.patient_id IS NOT NULL THEN (SELECT t9.middle_name FROM tbl_patients t9 WHERE t9.id=t1.patient_id  GROUP BY t1.patient_id) ELSE (SELECT t10.first_name FROM tbl_corpses t10 WHERE t10.id=t1.corpse_id  GROUP BY t1.corpse_id) END AS middle_name, 
	   
	   CASE WHEN t1.patient_id IS NOT NULL THEN (SELECT t9.last_name FROM tbl_patients t9 WHERE t9.id=t1.patient_id  GROUP BY t1.patient_id) ELSE (SELECT t10.first_name FROM tbl_corpses t10 WHERE t10.id=t1.corpse_id  GROUP BY t1.corpse_id) END AS last_name, 
	   
	  CASE WHEN t1.patient_id IS NOT NULL THEN (SELECT t9.medical_record_number FROM tbl_patients t9 WHERE t9.id=t1.patient_id  GROUP BY t1.patient_id) 
	  ELSE (SELECT t9.corpse_record_number FROM tbl_corpses t9 WHERE t9.id=t1.corpse_id  GROUP BY t1.corpse_id)
	  END AS medical_record_number, 
	   
	   CASE WHEN t1.patient_id IS NOT NULL THEN (SELECT t9.dob FROM tbl_patients t9 WHERE t9.id=t1.patient_id  GROUP BY t1.patient_id)
       ELSE (SELECT t9.dob FROM tbl_corpses t9 WHERE t9.id=t1.corpse_id  GROUP BY t1.corpse_id)   END AS dob,
	   
	   CASE WHEN t1.patient_id IS NOT NULL THEN (SELECT t9.gender FROM tbl_patients t9 WHERE t9.id=t1.patient_id  GROUP BY t1.patient_id) ELSE (SELECT t10.gender FROM tbl_corpses t10 WHERE t10.id=t1.corpse_id  GROUP BY t1.corpse_id) END AS gender,
	   
	   CASE WHEN t1.corpse_id IS NOT NULL THEN (SELECT CONCAT(t10.first_name,' ',t10.middle_name, ' ',t10.last_name, ' (',t10.corpse_record_number,')')  FROM tbl_corpses t10 WHERE t10.id=t1.corpse_id  GROUP BY t1.corpse_id) END AS corpse_name, 
	   
	   CASE WHEN t1.corpse_id IS NOT NULL THEN (SELECT t10.dod FROM tbl_corpses t10 WHERE t10.id=t1.corpse_id  GROUP BY t1.corpse_id) END AS dod,
	   
	   CASE WHEN t1.corpse_id IS NOT NULL THEN (SELECT t10.gender FROM tbl_corpses t10 WHERE t10.id=t1.corpse_id  GROUP BY t1.corpse_id) END AS corpse_gender,

	   CASE WHEN t1.corpse_id IS NOT NULL THEN (SELECT t10.corpse_record_number FROM tbl_corpses t10 WHERE t10.id=t1.corpse_id  GROUP BY t1.corpse_id) END AS corpse_record_number,
	   
	   	   
	   CASE WHEN t1.payment_filter IS NOT NULL THEN (SELECT t7.sub_category_name FROM tbl_pay_cat_sub_categories t7 WHERE t1.payment_filter=t7.id  GROUP BY t1.payment_filter) END AS sub_category_name,
	   
	   CASE WHEN t1.payment_filter IS NOT NULL THEN(SELECT t8.category_description FROM tbl_pay_cat_sub_categories t7
	   INNER JOIN tbl_payments_categories t8 ON t7.pay_cat_id = t8.id  WHERE t1.payment_filter=t7.id GROUP BY t1.payment_filter) END AS category_description,
	   
	   CASE WHEN t1.payment_filter IS NOT NULL THEN(SELECT t8.id AS main_category_id FROM tbl_pay_cat_sub_categories t7
	   
	   INNER JOIN tbl_payments_categories t8 ON t7.pay_cat_id = t8.id  WHERE t1.payment_filter=t7.id  GROUP BY t1.payment_filter) END AS main_category_id,
	   t4.item_name
	   	       		FROM tbl_invoice_lines t1
INNER JOIN tbl_item_type_mappeds t2 ON t1.item_type_id = t2.id 
INNER JOIN tbl_item_prices t3 ON t1.item_price_id = t3.id
INNER JOIN tbl_items t4 ON t3.item_id = t4.id 
INNER JOIN tbl_payment_statuses t5 ON t1.status_id = t5.id
INNER JOIN tbl_encounter_invoices t6 ON t1.invoice_id = t6.id 
ORDER BY t1.id ASC)";

     return DB::statement($vw_bills_payments);
					}
			
			
	public static function itemPrices(){
		            //make sure you pass facility ID to get Items Prices as you call this function
		            $itemPrices="CREATE OR REPLACE VIEW `vw_shop_items` AS ( SELECT
					t1.id AS item_id,
					t2.id AS item_type_id,
					t1.item_name,
					t1.dept_id,
					t2.item_category,
					t2.dose_formulation,
					t2.strength,
					t2.dispensing_unit,
					t2.sub_item_category,
					t3.exemption_status,
					t3.id AS price_id,
					t3.price,
					t3.facility_id ,
					t4.id AS patient_category_id,
					t4.pay_cat_id AS patient_main_category_id,
					t4.sub_category_name AS patient_category
					FROM  tbl_items t1,
					tbl_item_type_mappeds t2,
					tbl_item_prices t3,
					tbl_pay_cat_sub_categories t4
					WHERE 
					t1.id = t2.item_id  
					AND t3.item_id=t1.id 
					AND t4.id=t3.sub_category_id 			     
					 
					)";                    
       
					//var_dump($itemPrices);		
					 return DB::statement($itemPrices);
					
					}

        public static function labTests($facility_id){
            $tests = "CREATE OR REPLACE VIEW `vw_labTests` AS(SELECT
                t1.panel_compoent_name,
                t6.sub_item_category,
                t6.id AS item_type_id,
                t1.minimum_limit,
                t1.maximum_limit,
                t1.si_units,
                t2.id AS item_id,
				t3.equipment_name,
				t3.reagents,
				t3.sub_department_id,
				t5.department_id,
				t5.sub_department_name,
				t3.eraser,
				t3.facility_id,
                t4.status_name,
                t4.on_off				
                FROM 
				tbl_testspanels t1,tbl_items t2,tbl_equipments t3,tbl_equipment_statuses t4	,tbl_sub_departments t5, tbl_item_type_mappeds t6							
                WHERE				
				    t1.item_id=t2.id
                AND t3.id=t1.equipment_id
                AND t3.equipment_status_id=t4.id
                AND t5.id=t3.sub_department_id
                AND t2.id=t6.item_id
               
       )";
            return DB::statement($tests);
        }

		public static function createTreatmentChart(){
			$sql="CREATE OR REPLACE VIEW `vw_treatment_charts` AS (
SELECT t1.start_date AS date_dosage,
	   t3.item_name,
       t1.dose,
       t4.created_at AS time_given,
       t4.timedosage AS time_recorded,
       t4.remarks,
       t2.id AS admission_id,
       (SELECT name from users t6 WHERE t4.user_id=t6.id GROUP BY t6.id ) AS nurse_name
       FROM tbl_prescriptions t1
       INNER JOIN tbl_admissions t2 ON t1.visit_id=t2.account_id
       INNER JOIN tbl_items t3 ON t3.id=t1.item_id
       INNER JOIN tbl_ipdtreatments t4 ON t4.admission_id=t2.id)";

       return DB::statement($sql);
			
		}

        public static function equipmentsInfo($facility_id){
            $tests = "CREATE OR REPLACE VIEW `vw_equipments` AS(SELECT
              	t3.id,
              	t3.equipment_name,
				t3.reagents,
				t3.sub_department_id,
				t5.department_id,
				t5.sub_department_name,
				t3.eraser,
				t3.facility_id,
                t4.status_name,
                t4.on_off				
                FROM 
				tbl_equipments t3,tbl_equipment_statuses t4	,tbl_sub_departments t5							
                WHERE					
                    t3.equipment_status_id=t4.id
                AND t5.id=t3.sub_department_id
                   )";
            return DB::statement($tests);
        }

        public static function assignedPerms(){
            $tests = "CREATE OR REPLACE VIEW `vw_assigned_perms` AS(SELECT
                t2.id,
              	t1.title,
              	t1.glyphicons,
				t2.user_id,
				t2.grant				
               FROM tbl_permissions  t1
               INNER JOIN tbl_permission_users t2 ON t1.id=t2.permission_id                 
                               )";
            return DB::statement($tests);
        }


        public static function assignedPermsRoles(){
            $tests = "CREATE OR REPLACE VIEW `vw_assigned_perms_role` AS(SELECT
                t1.id,
              	t2.title,
              	t3.title AS role_name,
              	t2.glyphicons,
				t1.role_id,
				t1.grant				
               FROM tbl_permission_roles t1
               INNER JOIN tbl_permissions t2 ON t2.id=t1.permission_id                 
               INNER JOIN tbl_roles t3 ON t3.id=t1.role_id                 
                               )";
            return DB::statement($tests);
        }

    public static function getListForCtc(){
        $ctc_customers = "CREATE OR REPLACE VIEW `vw_ctc_customers` AS(SELECT
                t2.dept_id AS clinic_id,
              	t2.specialist_id,
              	t2.consultation_id,
              	t2.doctor_requesting_id,
              	TIMESTAMPDIFF(DAY,t2.created_at,CURRENT_TIMESTAMP) AS days_ago,
              	t2.id AS refferal_id,
              	t2.summary,
              	t2.priority,
              	t2.received,
              	t2.visit_id,
              	t3.facility_id,
              	t4.first_name,
              	t4.middle_name,
              	t4.last_name,
              	t4.gender,
              	t4.medical_record_number,
              	t4.residence_id,
              	t4.dob,
              	t4.id AS patient_id,
              	t5.name AS doctor						
               FROM tbl_clinic_instructions t2
               INNER JOIN tbl_accounts_numbers t3 ON t3.id=t2.visit_id                
               INNER JOIN tbl_patients t4 ON t4.id=t3.patient_id                
               INNER JOIN users t5 ON t5.id=t2.doctor_requesting_id               
                               )";
        return DB::statement($ctc_customers);
    }


    public static function getAttendanceListForCtc(){
            $ctc_customers = "CREATE OR REPLACE VIEW `vw_attendance_ctc_clinics` AS(SELECT
                t1.visit_id,
              	t1.next_visit,
              	t1.clinic_capacity AS clinic_capacity_id,
              	t1.follow_up_status,
              	t2.capacity,
              	t2.clinic_name_id AS clinic_id,
              	t3.department_name,
              	t4.facility_id,
              	t5.first_name,
              	t5.middle_name,
              	t5.last_name,
              	t5.gender,
              	t5.medical_record_number,
              	t5.residence_id,
              	t5.dob					
               FROM tbl_clinic_attendances t1
               INNER JOIN tbl_clinic_capacities t2 ON t2.id=t1.clinic_capacity                 
               INNER JOIN tbl_departments t3 ON t2.clinic_name_id=t3.id                 
               INNER JOIN tbl_accounts_numbers t4 ON t4.id=t1.visit_id                
               INNER JOIN tbl_patients t5 ON t5.id=t4.patient_id                
                              )";
            return DB::statement($ctc_customers);
        }



        public static function corpsesInside(){
            $wardCorpses = "CREATE OR REPLACE VIEW `vw_wardCorpses` AS(SELECT
              	t1.id AS corpse_admission_id,
              	t1.admission_status_id,
              	t1.admission_date AS disposed_date,
              	t1.updated_at AS disposed_date_time,
              	t1.cabinet_id,
              	t1.mortuary_id,
              	CONCAT(t2.first_name,' ',t2.middle_name,' ',t2.last_name) AS fullname,
              	t2.first_name,
				t2.middle_name,
				t2.last_name,
				t2.dob,
				t2.gender,
				t2.medical_record_number,
				t2.facility_id,
				t2.mobile_number,
				t2.residence_id,
                t2.tribe_id,
                t3.residence_name,
                t5.mortuary_name,
                t4.council_name                			
               FROM tbl_corpse_admissions  t1
               INNER JOIN tbl_patients t2 ON t1.patient_id=t2.id                 
               INNER JOIN tbl_residences t3 ON t2.residence_id=t3.id                 
               INNER JOIN tbl_councils t4 ON t3.council_id=t4.id 
               INNER JOIN tbl_mortuaries t5 ON t5.id=t1.mortuary_id 
                       )";
            return DB::statement($wardCorpses);
        }

	  public static function corpsesOutside(){
            $outCorpses = "CREATE OR REPLACE VIEW `vw_outsideCorpses` AS(SELECT
              	t1.id AS corpse_admission_id,
              	t1.admission_status_id,
              	t1.admission_date AS disposed_date,
              	t1.updated_at AS disposed_date_time,
              	t1.cabinet_id,
              	t1.mortuary_id,
              	CONCAT(t2.first_name,' ',t2.middle_name,' ',t2.last_name) AS fullname,
              	t2.first_name,
				t2.middle_name,
				t2.last_name,
				t2.dob,
				t2.gender,
				t2.corpse_record_number,
				t2.facility_id,
				t2.mobile_number,
				t2.residence_id,
                t2.tribe_id,
                t3.residence_name,
                t5.mortuary_name,
                t4.council_name                			
               FROM tbl_corpse_admissions  t1
               INNER JOIN tbl_corpses t2 ON t1.corpse_id=t2.id                 
               INNER JOIN tbl_residences t3 ON t2.residence_id=t3.id                 
               INNER JOIN tbl_councils t4 ON t3.council_id=t4.id 
               INNER JOIN tbl_mortuaries t5 ON t5.id=t1.mortuary_id 
                       )";
            return DB::statement($outCorpses);
        }

    public static function labRequests(){
        $labRequests = "CREATE OR REPLACE VIEW `vw_labRequests` AS ( SELECT 
        t1.patient_id,
        t1.visit_date_id,
        t2.order_id,
        t2.id AS request_id,
        t5.date_attended,
        t3.id AS item_id,
        t5.account_number,
        t6.first_name,
        t6.middle_name,
        t6.last_name,
        t6.gender,
        t6.dob,
        CASE WHEN TIMESTAMPDIFF(YEAR,dob, CURRENT_DATE) <> 0 THEN CONCAT(TIMESTAMPDIFF(YEAR,dob, CURRENT_DATE), ' Years') ELSE CASE WHEN TIMESTAMPDIFF(MONTH,dob, CURRENT_DATE) <> 0 THEN CONCAT(TIMESTAMPDIFF(MONTH, dob, CURRENT_DATE), ' Months') ELSE CONCAT(TIMESTAMPDIFF(DAY, dob, CURRENT_DATE), ' Days') END END
AS age,
        t6.medical_record_number,
        t6.mobile_number,
        t7.payment_filter,
        t7.status_id AS payment_status,
        t3.item_name,
        t18.sub_department_name,
        t18.id AS sub_department_id,
        t2.sample_no,
        t1.admission_id,
        CASE 
         WHEN t1.admission_id is NULL THEN 'OPD'  ELSE 'IPD'  END as dept, 
         
         COALESCE(
  CASE WHEN t7.status_id =2 THEN 'allowed' ELSE NULL END,
  CASE WHEN t7.status_id =1 AND t15.id >1 THEN 'allowed' ELSE NULL END
         ) AS test_status,
         
                  
        t2.created_at,
        t4.facility_id
        
         FROM tbl_orders  t2
            INNER JOIN tbl_requests t1 ON t1.id=t2.order_id
            INNER JOIN tbl_accounts_numbers t5 ON t1.visit_date_id =t5.id
            INNER JOIN tbl_items t3 ON t3.id = t2.test_id
            INNER JOIN users t4 ON t1.doctor_id = t4.id
            INNER JOIN tbl_patients t6 ON t1.patient_id = t6.id
            INNER JOIN tbl_encounter_invoices t8 ON t5.id = t8.account_number_id
            INNER JOIN tbl_invoice_lines t7 ON t7.invoice_id = t8.id
            INNER JOIN tbl_pay_cat_sub_categories t14 ON t14.id= t7.payment_filter
            INNER JOIN tbl_payments_categories t15 ON t14.pay_cat_id= t15.id
            INNER JOIN tbl_item_prices t13 ON t2.test_id = t13.item_id          
            INNER JOIN tbl_testspanels t16 ON t13.item_id = t16.item_id          
            INNER JOIN tbl_equipments t17 ON t17.id = t16.equipment_id          
            INNER JOIN tbl_sub_departments t18 ON t18.id = t17.sub_department_id          
           WHERE  
        t7.item_price_id = t13.id
        AND t2.test_id = t13.item_id
        AND t2.sample_no IS NULL
		GROUP BY t7.id  )";
        return DB::statement($labRequests);
    }


    public static function sampleNumberLists(){
        $labRequests_sample_number = "CREATE OR REPLACE VIEW `vw_sample_numbers` AS ( SELECT 
        t1.patient_id,
        t1.visit_date_id,
        t2.order_id,
        t2.id AS request_id,
        t5.date_attended,
        t3.id AS item_id,
        t5.account_number,
        t6.first_name,
        t6.middle_name,
        t6.last_name,
        t6.gender,
        t6.dob,
        CASE WHEN TIMESTAMPDIFF(YEAR,dob, CURRENT_DATE) <> 0 THEN CONCAT(TIMESTAMPDIFF(YEAR,dob, CURRENT_DATE), ' Years') ELSE CASE WHEN TIMESTAMPDIFF(MONTH,dob, CURRENT_DATE) <> 0 THEN CONCAT(TIMESTAMPDIFF(MONTH, dob, CURRENT_DATE), ' Months') ELSE CONCAT(TIMESTAMPDIFF(DAY, dob, CURRENT_DATE), ' Days') END END
AS age,
        t6.medical_record_number,
        t6.mobile_number,
        t7.payment_filter,
        t7.status_id AS payment_status,
        t3.item_name,
        t2.sample_no,
        t12.sub_department_name,
        t12.id AS sub_department_id,
        t1.admission_id,
        CASE 
         WHEN t1.admission_id is NULL THEN 'OPD'  ELSE 'IPD'  END as dept,        
        t2.created_at,
        t4.facility_id
        
         FROM tbl_orders  t2
            INNER JOIN tbl_requests t1 ON t1.id=t2.order_id
            INNER JOIN tbl_accounts_numbers t5 ON t1.visit_date_id =t5.id
            INNER JOIN tbl_items t3 ON t3.id = t2.test_id
            INNER JOIN users t4 ON t1.doctor_id = t4.id
            INNER JOIN tbl_patients t6 ON t1.patient_id = t6.id
            INNER JOIN tbl_encounter_invoices t8 ON t5.id = t8.account_number_id
            INNER JOIN tbl_invoice_lines t7 ON t7.invoice_id = t8.id
            INNER JOIN tbl_testspanels t9 ON t2.test_id = t9.item_id
            INNER JOIN tbl_equipments t10 ON t10.id = t9.equipment_id
            INNER JOIN tbl_equipment_statuses t11 ON t11.id = t10.equipment_status_id
            INNER JOIN tbl_sub_departments  t12 ON t12.id=t10.sub_department_id     
            INNER JOIN tbl_item_prices t13 ON t2.test_id = t13.item_id          
           WHERE  
        t7.item_price_id = t13.id
        AND t2.test_id = t13.item_id)";
        return DB::statement($labRequests_sample_number);
    }


    public static function collectedSamples(){
        $collectedSamples= "CREATE OR REPLACE VIEW `vw_collectedSamples` AS (SELECT 
        t1.patient_id,
        t1.visit_date_id,
        t2.order_id,
        t2.order_status,
        t2.order_control,
        t2.id AS request_id,
        t5.date_attended,
        t3.id AS item_id,
        t5.account_number,
        t6.first_name,
        t6.middle_name,
        t6.last_name,
        t6.gender,
        t6.dob,
        CASE WHEN TIMESTAMPDIFF(YEAR,dob, CURRENT_DATE) <> 0 THEN CONCAT(TIMESTAMPDIFF(YEAR,dob, CURRENT_DATE), ' Years') ELSE CASE WHEN TIMESTAMPDIFF(MONTH,dob, CURRENT_DATE) <> 0 THEN CONCAT(TIMESTAMPDIFF(MONTH, dob, CURRENT_DATE), ' Months') ELSE CONCAT(TIMESTAMPDIFF(DAY, dob, CURRENT_DATE), ' Days') END END
AS age,
        t6.medical_record_number,
        t6.mobile_number,
		(SELECT name FROM users t11 WHERE t11.id=t1.doctor_id GROUP BY t1.doctor_id ) AS doctor_name,
		(CASE WHEN t2.order_status=2 AND t2.result_control=1 THEN 'verified'
	    WHEN t2.order_status=1 AND t2.order_validator_id IS NULL THEN 'Not Verified'	
	   	ELSE 'Waiting for Verification' END ) AS sample_status,
		(SELECT mobile_number FROM users t11 WHERE t11.id=t1.doctor_id GROUP BY t1.doctor_id ) AS doctor_mobile_number,
		
		(SELECT t12.created_at FROM tbl_sample_number_controls t12 WHERE
		TRIM(LEADING '0' FROM t12.sample_no)=t2.sample_no GROUP BY t2.sample_no) AS time_collected,
		
		(SELECT t13.name FROM users t13 INNER JOIN
     	tbl_sample_number_controls t12 ON t12.user_id=t13.id
		WHERE TRIM(LEADING '0' FROM t12.sample_no)=t2.sample_no GROUP BY t2.sample_no) AS collected_by,
		
		(SELECT CONCAT(t14.facility_name,' ',t12.description) FROM tbl_facilities t14 INNER JOIN
     	tbl_facility_types t12 ON t12.id=t14.facility_type_id
		WHERE t14.id=t5.facility_id) AS facility_name,
		
		(SELECT t14.address FROM tbl_facilities t14 
		WHERE t14.id=t5.facility_id) AS facility_address,
		
				
		(SELECT t12.council_name FROM tbl_facilities t14 INNER JOIN
     	tbl_councils t12 ON t12.id=t14.council_id
		WHERE t14.id=t5.facility_id) AS council_name,
		
		(SELECT t14.residence_name FROM tbl_residences t14 
		INNER JOIN tbl_councils t12 ON t12.id=t14.council_id
		WHERE t14.council_id=t12.id AND t6.residence_id=t14.id) AS residence_name,
		
        t3.item_name,
        t9.minimum_limit,
        t9.maximum_limit,
        t9.si_units,
        t2.sample_no,
        t12.sub_department_name,
        t12.id AS sub_department_id,
        t1.admission_id,
        CASE 
         WHEN t1.admission_id is NULL THEN 'OPD'  ELSE 'IPD'  END as dept,        
        t2.created_at,
        t2.sample_types,
        t2.clinical_note,
        t2.created_at AS time_requested,
        t4.facility_id        
         FROM tbl_orders  t2
            INNER JOIN tbl_requests t1 ON t1.id=t2.order_id
            INNER JOIN tbl_accounts_numbers t5 ON t1.visit_date_id =t5.id
            INNER JOIN tbl_items t3 ON t3.id = t2.test_id
            INNER JOIN users t4 ON t1.doctor_id = t4.id
            INNER JOIN tbl_patients t6 ON t1.patient_id = t6.id
            INNER JOIN tbl_testspanels t9 ON t2.test_id = t9.item_id
             INNER JOIN tbl_equipments t10 ON t10.id = t9.equipment_id
            INNER JOIN tbl_sub_departments  t12 ON t12.id=t10.sub_department_id              
            WHERE t2.test_id=t9.item_id AND t2.sample_no is NOT NULL)";
        return DB::statement($collectedSamples);
    }

    public static function testsResultsDone(){
        $collectedSamples= "CREATE OR REPLACE VIEW `vw_testResults` AS ( SELECT 
        t1.patient_id,
        t1.visit_date_id,
        t2.order_id,
        t2.order_status,
        t2.order_control,
        t2.id AS request_id,
        t5.date_attended,
        t3.id AS item_id,
        t5.account_number,
        t6.first_name,
        t6.middle_name,
        t6.last_name,
        t6.gender,
        t6.dob,
        CASE WHEN TIMESTAMPDIFF(YEAR,dob, CURRENT_DATE) <> 0 THEN CONCAT(TIMESTAMPDIFF(YEAR,dob, CURRENT_DATE), ' Years') ELSE CASE WHEN TIMESTAMPDIFF(MONTH,dob, CURRENT_DATE) <> 0 THEN CONCAT(TIMESTAMPDIFF(MONTH, dob, CURRENT_DATE), ' Months') ELSE CONCAT(TIMESTAMPDIFF(DAY, dob, CURRENT_DATE), ' Days') END END
AS age,
        t6.medical_record_number,
        t6.mobile_number,
        t7.status_id AS payment_status,
        t3.item_name,
        t9.minimum_limit,
        t9.maximum_limit,
        t9.si_units,
        t2.sample_no,
        t12.sub_department_name,
        t12.id AS sub_department_id,
        t13.post_user,
        t13.post_time,
        t13.description,
        t13.confirmation_status,
        t1.admission_id,
        CASE 
         WHEN t1.admission_id is NULL THEN 'OPD'  ELSE 'IPD'  END as dept,        
        t2.created_at,
        t4.facility_id        
         FROM tbl_orders  t2
            INNER JOIN tbl_requests t1 ON t1.id=t2.order_id
            INNER JOIN tbl_accounts_numbers t5 ON t1.visit_date_id =t5.id
            INNER JOIN tbl_items t3 ON t3.id = t2.test_id
            INNER JOIN users t4 ON t1.doctor_id = t4.id
            INNER JOIN tbl_patients t6 ON t1.patient_id = t6.id
            INNER JOIN tbl_encounter_invoices t8 ON t5.id = t8.account_number_id
            INNER JOIN tbl_invoice_lines t7 ON t7.invoice_id = t8.id
            INNER JOIN tbl_testspanels t9 ON t2.test_id = t9.item_id
            INNER JOIN tbl_equipments t10 ON t10.id = t9.equipment_id
            INNER JOIN tbl_equipment_statuses t11 ON t11.id = t10.equipment_status_id
            INNER JOIN tbl_sub_departments  t12 ON t12.id=t10.sub_department_id              
            INNER JOIN tbl_results  t13 ON t13.order_id=t2.id            
            WHERE t2.test_id=t9.item_id AND t13.confirmation_status IS NULL AND t2.sample_no is NOT NULL)";
        return DB::statement($collectedSamples);
    }

       public static function approvedResultsDone(){
        $collectedSamples= "CREATE OR REPLACE VIEW `vw_approvedResults` AS ( SELECT 
        t1.patient_id,
        t1.visit_date_id,
        t2.order_id,
        t2.order_status,
        t2.order_control,
        t2.id AS request_id,
        t5.date_attended,
        t3.id AS item_id,
        t5.account_number,
        t6.first_name,
        t6.middle_name,
        t6.last_name,
        t6.gender,
        t6.dob,
        CASE WHEN TIMESTAMPDIFF(YEAR,dob, CURRENT_DATE) <> 0 THEN CONCAT(TIMESTAMPDIFF(YEAR,dob, CURRENT_DATE), ' Years') ELSE CASE WHEN TIMESTAMPDIFF(MONTH,dob, CURRENT_DATE) <> 0 THEN CONCAT(TIMESTAMPDIFF(MONTH, dob, CURRENT_DATE), ' Months') ELSE CONCAT(TIMESTAMPDIFF(DAY, dob, CURRENT_DATE), ' Days') END END
AS age,
        t6.medical_record_number,
        t6.mobile_number,
        t7.status_id AS payment_status,
        t3.item_name,
        t9.minimum_limit,
        t9.maximum_limit,
        t9.si_units,
        t2.sample_no,
        t12.sub_department_name,
        t12.id AS sub_department_id,
        t13.post_user,
        t13.post_time,
        t13.confirmation_status,
        t1.admission_id,
        CASE 
         WHEN t1.admission_id is NULL THEN 'OPD'  ELSE 'IPD'  END as dept,        
        t2.created_at,
        t4.facility_id        
         FROM tbl_orders  t2
            INNER JOIN tbl_requests t1 ON t1.id=t2.order_id
            INNER JOIN tbl_accounts_numbers t5 ON t1.visit_date_id =t5.id
            INNER JOIN tbl_items t3 ON t3.id = t2.test_id
            INNER JOIN users t4 ON t1.doctor_id = t4.id
            INNER JOIN tbl_patients t6 ON t1.patient_id = t6.id
            INNER JOIN tbl_encounter_invoices t8 ON t5.id = t8.account_number_id
            INNER JOIN tbl_invoice_lines t7 ON t7.invoice_id = t8.id
            INNER JOIN tbl_testspanels t9 ON t2.test_id = t9.item_id
            INNER JOIN tbl_equipments t10 ON t10.id = t9.equipment_id
            INNER JOIN tbl_equipment_statuses t11 ON t11.id = t10.equipment_status_id
            INNER JOIN tbl_sub_departments  t12 ON t12.id=t10.sub_department_id              
            INNER JOIN tbl_results  t13 ON t13.order_id=t2.id            
            WHERE t2.test_id=t9.item_id AND t13.confirmation_status =1 AND t2.sample_no is NOT NULL)";
        return DB::statement($collectedSamples);
    }

    public  static function PatientWardBed(){
        $cont =" CREATE OR REPLACE VIEW `vw_patient_ward_beds` AS ( SELECT
        t1.admission_id,
        t2.ward_full_name,
        t2.bed_available,
        t2.facility_id
        
        FROM tbl_instructions t1,vw_beds t2
        WHERE
        t2.bed_id = t1.bed_id
       
        )";
        return DB::statement($cont);
    }
	//start view
	public static function patientsToPoS(){
        $pos = "CREATE OR REPLACE VIEW `vw_patients_to_pos` AS (SELECT
        t1.id AS patient_id,
        t1.first_name,
        t1.middle_name,
        t1.last_name,
        t1.gender,
        t1.dob,
        t1.medical_record_number,
        t2.account_number,
        t2.id AS account_id,
        t2.facility_id,
        t3.id AS invoice_id,
        t4.main_category_id,        
        t5.sub_category_name,
        t6.category_description,
        t4.bill_id AS patient_category_id
        FROM tbl_patients t1, tbl_accounts_numbers t2, tbl_encounter_invoices t3,tbl_bills_categories t4,
        tbl_pay_cat_sub_categories t5,tbl_payments_categories t6
        WHERE t1.id = t2.patient_id
        AND t2.id = t3.account_number_id
        AND t2.id = t4.account_id 
        AND t5.id = t4.bill_id 
        AND t6.id = t4.main_category_id group by t1.id ORDER BY t4.id DESC
        )";
        return DB::statement($pos);
    }
	
	public static function createViewMarudioOpdAttendaces(){

        $opdmarudioAttendance="CREATE OR REPLACE VIEW vw_opd_marudio_attendaces AS(
        SELECT t.date_attended as date_attended,t.facility_id,

(SELECT COUNT(gender) FROM `tbl_patients` INNER JOIN `tbl_accounts_numbers` 
	ON `tbl_accounts_numbers`.patient_id =`tbl_patients`.id AND `tbl_patients`.gender = 'Female'
	AND TIMESTAMPDIFF(MONTH, `tbl_patients`.dob, date_attended) < 1 WHERE date_attended=t.date_attended AND date_attended<>DATE(tbl_patients.created_at)) AS female_under_one_month,
(SELECT COUNT(gender) FROM `tbl_patients` INNER JOIN `tbl_accounts_numbers` 
	ON `tbl_accounts_numbers`.patient_id = 	`tbl_patients`.id AND `tbl_patients`.gender = 'Male'
	AND TIMESTAMPDIFF(MONTH, `tbl_patients`.dob, date_attended) < 1 WHERE date_attended=t.date_attended AND date_attended<>DATE(tbl_patients.created_at)) AS male_under_one_month,
(SELECT COUNT(gender) FROM `tbl_patients` INNER JOIN `tbl_accounts_numbers` 
	ON `tbl_accounts_numbers`.patient_id = 	`tbl_patients`.id
	AND TIMESTAMPDIFF(MONTH, `tbl_patients`.dob, date_attended) < 1 WHERE date_attended=t.date_attended AND date_attended<>DATE(tbl_patients.created_at)) AS total_under_one_month,
	
	(SELECT COUNT(gender) FROM `tbl_patients` INNER JOIN `tbl_accounts_numbers` 
	ON `tbl_accounts_numbers`.patient_id = 	`tbl_patients`.id AND `tbl_patients`.gender = 'Female'
	AND TIMESTAMPDIFF(MONTH, `tbl_patients`.dob, date_attended) BETWEEN 1 AND 11 WHERE date_attended=t.date_attended AND date_attended<>DATE(tbl_patients.created_at)) AS female_under_one_year,
(SELECT COUNT(gender) FROM `tbl_patients` INNER JOIN `tbl_accounts_numbers` 
	ON `tbl_accounts_numbers`.patient_id = 	`tbl_patients`.id AND `tbl_patients`.gender = 'Male'
	AND TIMESTAMPDIFF(MONTH, `tbl_patients`.dob, date_attended) BETWEEN 1 AND 11 WHERE date_attended=t.date_attended AND date_attended<>DATE(tbl_patients.created_at)) AS male_under_one_year,
(SELECT COUNT(gender) FROM `tbl_patients` INNER JOIN `tbl_accounts_numbers` 
	ON `tbl_accounts_numbers`.patient_id = 	`tbl_patients`.id 
	AND TIMESTAMPDIFF(MONTH, `tbl_patients`.dob, date_attended) BETWEEN 1 AND 11 WHERE date_attended=t.date_attended AND date_attended<>DATE(tbl_patients.created_at)) AS total_under_one_year,
	
	(SELECT COUNT(gender) FROM `tbl_patients` INNER JOIN `tbl_accounts_numbers` 
	ON `tbl_accounts_numbers`.patient_id = 	`tbl_patients`.id AND `tbl_patients`.gender = 'Female'
	AND TIMESTAMPDIFF(MONTH, `tbl_patients`.dob, date_attended) BETWEEN 12 AND 59 WHERE date_attended=t.date_attended AND date_attended<>DATE(tbl_patients.created_at)) AS female_under_five_year,
(SELECT COUNT(gender) FROM `tbl_patients` INNER JOIN `tbl_accounts_numbers` 
	ON `tbl_accounts_numbers`.patient_id = 	`tbl_patients`.id AND `tbl_patients`.gender = 'Male'
	AND TIMESTAMPDIFF(MONTH, `tbl_patients`.dob, date_attended) BETWEEN 12 AND 59 WHERE date_attended=t.date_attended AND date_attended<>DATE(tbl_patients.created_at)) AS male_under_five_year,
	
(SELECT COUNT(gender) FROM `tbl_patients` INNER JOIN `tbl_accounts_numbers` 
	ON `tbl_accounts_numbers`.patient_id = 	`tbl_patients`.id
	AND TIMESTAMPDIFF(MONTH, `tbl_patients`.dob, date_attended) BETWEEN 12 AND 59 WHERE date_attended=t.date_attended AND date_attended<>DATE(tbl_patients.created_at)) AS total_under_five_year,
	
	(SELECT COUNT(gender) FROM `tbl_patients` INNER JOIN `tbl_accounts_numbers` 
	ON `tbl_accounts_numbers`.patient_id = 	`tbl_patients`.id AND `tbl_patients`.gender = 'Female'
	AND TIMESTAMPDIFF(MONTH, `tbl_patients`.dob, date_attended) BETWEEN 60 AND 719 WHERE date_attended=t.date_attended AND date_attended<>DATE(tbl_patients.created_at)) AS female_above_five_under_sixty_year,
	
(SELECT COUNT(gender) FROM `tbl_patients` INNER JOIN `tbl_accounts_numbers` 
	ON `tbl_accounts_numbers`.patient_id = 	`tbl_patients`.id AND `tbl_patients`.gender = 'Male'
	AND TIMESTAMPDIFF(MONTH, `tbl_patients`.dob, date_attended) BETWEEN 60 AND 719 WHERE date_attended=t.date_attended AND date_attended<>DATE(tbl_patients.created_at)) AS male_above_five_under_sixty,
	
(SELECT COUNT(gender) FROM `tbl_patients` INNER JOIN `tbl_accounts_numbers` 
	ON `tbl_accounts_numbers`.patient_id = 	`tbl_patients`.id
	AND TIMESTAMPDIFF(MONTH, `tbl_patients`.dob, date_attended) BETWEEN 60 AND 719 WHERE date_attended=t.date_attended AND date_attended<>DATE(tbl_patients.created_at)) AS total_above_five_under_sixty,
	
	(SELECT COUNT(gender) FROM `tbl_patients` INNER JOIN `tbl_accounts_numbers` 
	ON `tbl_accounts_numbers`.patient_id = 	`tbl_patients`.id AND `tbl_patients`.gender = 'Female'
	AND TIMESTAMPDIFF(MONTH, `tbl_patients`.dob, date_attended) >=720 WHERE date_attended=t.date_attended AND date_attended <> DATE(tbl_patients.created_at)) AS female_above_sixty,
	
(SELECT COUNT(gender) FROM `tbl_patients` INNER JOIN `tbl_accounts_numbers` 
	ON `tbl_accounts_numbers`.patient_id = 	`tbl_patients`.id AND `tbl_patients`.gender = 'Male'
	AND TIMESTAMPDIFF(MONTH, `tbl_patients`.dob, date_attended) >=720 WHERE date_attended=t.date_attended AND date_attended  <> DATE(tbl_patients.created_at)) AS male_above_sixty,
	
(SELECT COUNT(gender) FROM `tbl_patients` INNER JOIN `tbl_accounts_numbers` 
	ON `tbl_accounts_numbers`.patient_id = 	`tbl_patients`.id
	AND TIMESTAMPDIFF(MONTH, `tbl_patients`.dob, date_attended) >=720 WHERE date_attended=t.date_attended AND date_attended <>DATE(tbl_patients.created_at)) AS total_above_sixty,
	
	(SELECT COUNT(gender) FROM `tbl_patients` INNER JOIN `tbl_accounts_numbers` 
	ON `tbl_accounts_numbers`.patient_id = 	`tbl_patients`.id AND `tbl_patients`.gender = 'Female' WHERE date_attended=t.date_attended AND date_attended<>DATE(tbl_patients.created_at)) AS grand_total_female,
	
	(SELECT COUNT(gender) FROM `tbl_patients` INNER JOIN `tbl_accounts_numbers` 
	ON `tbl_accounts_numbers`.patient_id = 	`tbl_patients`.id AND `tbl_patients`.gender = 'Male' WHERE date_attended=t.date_attended AND date_attended<>DATE(tbl_patients.created_at)) AS grand_total_male,
	
	(SELECT COUNT(gender) FROM `tbl_patients` INNER JOIN `tbl_accounts_numbers` 
	ON `tbl_accounts_numbers`.patient_id = 	`tbl_patients`.id WHERE date_attended=t.date_attended AND date_attended<>DATE(tbl_patients.created_at)) AS grand_total
	FROM `tbl_accounts_numbers` AS t GROUP BY date_attended,t.facility_id
        )";

	 return DB::statement($opdmarudioAttendance);
	 }
	
    public static function opdPatients(){
        $opdPatients = "CREATE OR REPLACE VIEW `vw_opd_patients` AS (SELECT 
        t1.id AS patient_id,
        t1.first_name,
        t1.middle_name,
        t1.last_name,
        t1.medical_record_number,
        t1.dob,
        CASE WHEN TIMESTAMPDIFF(YEAR,dob, CURRENT_DATE) <> 0 THEN CONCAT(TIMESTAMPDIFF(YEAR,dob, CURRENT_DATE), ' Years') ELSE CASE WHEN TIMESTAMPDIFF(MONTH,dob, CURRENT_DATE) <> 0 THEN CONCAT(TIMESTAMPDIFF(MONTH, dob, CURRENT_DATE), ' Months') ELSE CONCAT(TIMESTAMPDIFF(DAY, dob, CURRENT_DATE), ' Days') END END
AS age,
        (TIMESTAMPDIFF(YEAR,t1.dob, CURRENT_DATE)) AS umri,
        t1.gender,
        t2.account_number,
        t2.status,
        t2.id AS account_id,
        t2.date_attended AS visit_date,
        t3.status_id AS payment_status_id,
        t3.payment_filter,
        t4.main_category_id,
		t4.bill_id,
        t2.facility_id
        FROM tbl_patients t1
		INNER JOIN tbl_accounts_numbers t2 ON t1.id = t2.patient_id
		INNER JOIN tbl_invoice_lines t3 ON t3.patient_id = t2.patient_id 
		INNER JOIN tbl_bills_categories t4 ON t4.account_id = t2.id 
		 WHERE (timestampdiff(hour,t2.updated_at,CURRENT_TIMESTAMP))<= 24
		 GROUP BY t2.id
          ORDER BY t2.created_at DESC)";
        return DB::statement($opdPatients);
    }
	public static function ipdPatients(){
        $ipdPatients = "CREATE OR REPLACE VIEW `vw_ipd_patients` AS (SELECT 
        t1.id AS patient_id,
        t1.first_name,
        t1.middle_name,
        t1.last_name,
        t1.medical_record_number,
        t1.dob,
        CASE WHEN TIMESTAMPDIFF(YEAR,dob, CURRENT_DATE) <> 0 THEN CONCAT(TIMESTAMPDIFF(YEAR,dob, CURRENT_DATE), ' Years') ELSE CASE WHEN TIMESTAMPDIFF(MONTH,dob, CURRENT_DATE) <> 0 THEN CONCAT(TIMESTAMPDIFF(MONTH, dob, CURRENT_DATE), ' Months') ELSE CONCAT(TIMESTAMPDIFF(DAY, dob, CURRENT_DATE), ' Days') END END
AS age,(TIMESTAMPDIFF(YEAR,t1.dob, CURRENT_DATE)) AS umri,
        t1.gender,
        t2.account_number,
        t2.id AS account_id,
        t2.date_attended AS visit_date,
        t3.id AS admission_id,
        t3.admission_status_id,
        t4.main_category_id,
        t4.bill_id,
        t6.ward_id,
        t2.facility_id
        FROM tbl_patients t1,tbl_accounts_numbers t2,tbl_admissions t3,tbl_bills_categories t4,tbl_admission_statuses t5,tbl_instructions t6
        WHERE 
            t1.id = t2.patient_id
        AND t1.id = t3.patient_id
        AND t2.id = t4.account_id
        AND t5.id = t3.admission_status_id
        AND t3.id = t6.admission_id
        )";
        return DB::statement($ipdPatients);
    }
	 public static function detailedReports(){
        $detailed_reports="CREATE OR REPLACE VIEW `vw_detailed_reports` AS ( 

SELECT t1.id, t1.invoice_id, t1.item_type_id, 

t1.quantity, t1.item_price_id, t1.user_id, 

t1.patient_id, t1.status_id, t1.facility_id, 

t1.discount, t1.discount_by, t1.payment_filter, 

t1.gepg_receipt, t1.payment_method_id, t1.updated_at as 

created_at, t1.updated_at,        

t2.item_category,t3.price, t3.price AS unit_price, 	

   t1.id AS item_refference,  t6.id AS receipt_number,  

CASE WHEN t1.patient_id IS NOT NULL THEN (SELECT 

t9.first_name FROM tbl_patients t9 WHERE 

t9.id=t1.patient_id  GROUP BY t1.patient_id) ELSE 

(SELECT t10.first_name FROM tbl_corpses t10 WHERE 

t10.id=t1.corpse_id  GROUP BY t1.corpse_id) END AS 

first_name, CASE WHEN t1.patient_id IS NOT NULL THEN 

(SELECT t9.middle_name FROM tbl_patients t9 WHERE 

t9.id=t1.patient_id  GROUP BY t1.patient_id) ELSE 

(SELECT t10.first_name FROM tbl_corpses t10 WHERE 

t10.id=t1.corpse_id  GROUP BY t1.corpse_id) END AS 

middle_name, CASE WHEN t1.patient_id IS NOT NULL THEN 

(SELECT t9.last_name FROM tbl_patients t9 WHERE 

t9.id=t1.patient_id  GROUP BY t1.patient_id) ELSE 

(SELECT t10.first_name FROM tbl_corpses t10 WHERE 

t10.id=t1.corpse_id  GROUP BY t1.corpse_id) END AS 

last_name, CASE WHEN t1.patient_id IS NOT NULL THEN 

(SELECT t9.medical_record_number FROM tbl_patients t9 

WHERE t9.id=t1.patient_id  GROUP BY t1.patient_id) END 

AS medical_record_number, CASE WHEN t1.patient_id IS 

NOT NULL THEN (SELECT t9.dob FROM tbl_patients t9 WHERE 

t9.id=t1.patient_id  GROUP BY t1.patient_id) END AS 

dob, CASE WHEN t1.patient_id IS NOT NULL THEN (SELECT 

t9.gender FROM tbl_patients t9 WHERE 

t9.id=t1.patient_id  GROUP BY t1.patient_id) ELSE 

(SELECT t10.gender FROM tbl_corpses t10 WHERE 

t10.id=t1.corpse_id  GROUP BY t1.corpse_id) END AS 

gender, CASE WHEN t1.corpse_id IS NOT NULL THEN (SELECT 

CONCAT(t10.first_name,' ',t10.middle_name, ' 

',t10.last_name, ' (',t10.corpse_record_number,')')  

FROM tbl_corpses t10 WHERE t10.id=t1.corpse_id  GROUP 

BY t1.corpse_id) END AS corpse_name, CASE WHEN 

t1.corpse_id IS NOT NULL THEN (SELECT t10.dod FROM 

tbl_corpses t10 WHERE t10.id=t1.corpse_id  GROUP BY 

t1.corpse_id) END AS dod, CASE WHEN t1.corpse_id IS NOT 

NULL THEN (SELECT t10.gender FROM tbl_corpses t10 WHERE 

t10.id=t1.corpse_id  GROUP BY t1.corpse_id) END AS 

corpse_gender,  CASE WHEN t1.corpse_id IS NOT NULL THEN 

(SELECT t10.corpse_record_number FROM tbl_corpses t10 

WHERE t10.id=t1.corpse_id  GROUP BY t1.corpse_id) END 

AS corpse_record_number, CASE WHEN t1.user_id IS NOT 

NULL THEN (SELECT t11.name FROM users t11 WHERE 

t11.id=t1.user_id  GROUP BY t1.user_id) END AS 

user_name, CASE WHEN t1.payment_filter IS NOT NULL THEN 

(SELECT t7.sub_category_name FROM 

tbl_pay_cat_sub_categories t7 WHERE 

t1.payment_filter=t7.id  GROUP BY t1.payment_filter) 

END AS sub_category_name, CASE WHEN t1.payment_filter 

IS NOT NULL THEN(SELECT t8.category_description FROM 

tbl_pay_cat_sub_categories t7 INNER JOIN 

tbl_payments_categories t8 ON t7.pay_cat_id = t8.id  

WHERE t1.payment_filter=t7.id GROUP BY 

t1.payment_filter) END AS category_description, CASE 

WHEN t1.payment_filter IS NOT NULL THEN(SELECT t8.id AS 

main_category_id FROM tbl_pay_cat_sub_categories t7 

INNER JOIN tbl_payments_categories t8 ON t7.pay_cat_id 

= t8.id  WHERE t1.payment_filter=t7.id  GROUP BY 

t1.payment_filter) END AS main_category_id, 

t4.item_name FROM tbl_invoice_lines t1 INNER JOIN 

tbl_item_type_mappeds t2 ON t1.item_type_id = t2.id 

INNER JOIN tbl_item_prices t3 ON t1.item_price_id = 

t3.id INNER JOIN tbl_items t4 ON t3.item_id = t4.id 

INNER JOIN tbl_payment_statuses t5 ON t1.status_id = 

t5.id INNER JOIN tbl_encounter_invoices t6 ON 

t1.invoice_id = t6.id  where t1.status_id=2 )";
        return DB::statement($detailed_reports);
    }

    public static function pendingBills()
    {
        $pendingBills="CREATE OR REPLACE VIEW `vw_pending_bills` AS (  SELECT 					
					tbl_invoice_lines.id,
					tbl_invoice_lines.id AS item_refference,
					tbl_encounter_invoices.id AS receipt_number,					
					tbl_invoice_lines.invoice_id,
					tbl_invoice_lines.patient_id,
					tbl_invoice_lines.status_id,
					tbl_patients.medical_record_number,
					tbl_patients.dob,
					tbl_patients.gender,
					tbl_patients.first_name,
					tbl_patients.middle_name,
					tbl_patients.last_name,
					tbl_patients.mobile_number,					
					tbl_invoice_lines.quantity, 
	                tbl_invoice_lines.discount, 
	                tbl_invoice_lines.facility_id,
	                tbl_invoice_lines.discount_by,
	                tbl_invoice_lines.payment_filter,
	                tbl_invoice_lines.payment_method_id,
	                tbl_bills_categories.main_category_id,
	                tbl_bills_categories.bill_id,
	                tbl_invoice_lines.gepg_receipt,
	                tbl_invoice_lines.created_at,
	                tbl_item_prices.price,
	                tbl_items.item_name, 
	                users.id as user_id,
	                users.name as user_name,
	                tbl_item_type_mappeds.item_category,
	                tbl_accounts_numbers.account_number, tbl_payment_statuses.payment_status 
	FROM tbl_invoice_lines INNER JOIN tbl_item_type_mappeds ON tbl_invoice_lines.item_type_id = tbl_item_type_mappeds.id 
	INNER JOIN tbl_item_prices ON tbl_invoice_lines.item_price_id = tbl_item_prices.ID
	INNER JOIN tbl_items ON tbl_item_prices.item_id = tbl_items.id 
	INNER JOIN tbl_payment_statuses ON tbl_invoice_lines.status_id = tbl_payment_statuses.ID 
	INNER JOIN tbl_encounter_invoices ON tbl_invoice_lines.invoice_id = tbl_encounter_invoices.id 		
	INNER JOIN tbl_accounts_numbers ON tbl_encounter_invoices.account_number_id = tbl_accounts_numbers.ID 
	INNER JOIN tbl_patients ON tbl_accounts_numbers.patient_id = tbl_patients.id 
	INNER JOIN users ON tbl_invoice_lines.user_id = users.id
	INNER JOIN tbl_bills_categories ON tbl_accounts_numbers.id = tbl_bills_categories.account_id
					)";
        return DB::statement($pendingBills);
    }

    public static function referrals()
    {
        $ref = "CREATE OR REPLACE VIEW `vw_referrals` AS(SELECT
                    tbl_patients.id AS patient_id,
                    tbl_patients.dob,
					 CASE WHEN TIMESTAMPDIFF(YEAR,dob, CURRENT_DATE) <> 0 THEN CONCAT(TIMESTAMPDIFF(YEAR,dob, CURRENT_DATE), ' Years') ELSE CASE WHEN TIMESTAMPDIFF(MONTH,dob, CURRENT_DATE) <> 0 THEN CONCAT(TIMESTAMPDIFF(MONTH, dob, CURRENT_DATE), ' Months') ELSE CONCAT(TIMESTAMPDIFF(DAY, dob, CURRENT_DATE), ' Days') END END
AS age,
					tbl_patients.gender,
					tbl_patients.first_name,
					tbl_patients.middle_name,
					tbl_patients.last_name,
					tbl_patients.medical_record_number,
					tbl_referrals.to_facility_id as facility_id,
					tbl_referrals.created_at,
					tbl_facilities.facility_name AS sender_facility,
					tbl_referrals.from_facility_id AS sender_facility_id,
					users.name AS doctor_name,
					users.mobile_number AS doctor_number,
					users.email AS doctor_email,
					tbl_referrals.summary,
					tbl_referrals.referral_type,
					tbl_referrals.visit_id,
					tbl_referrals.status
					FROM tbl_referrals INNER JOIN tbl_patients ON tbl_referrals.patient_id = tbl_patients.id
					INNER JOIN tbl_facilities ON tbl_referrals.from_facility_id=tbl_facilities.id
					INNER JOIN users ON tbl_referrals.sender_id = users.id
					
					)";
        return DB::statement($ref);
	}
	

public static function createViewStaffPermance(){

        $opdAttendance="CREATE OR REPLACE VIEW vw_staff_perfomances AS(
        SELECT t.date_attended as date_attended,COUNT(user_id) AS number_registered,t.user_id,t.facility_id       
          		
	  FROM `tbl_accounts_numbers` t GROUP BY date_attended,t.user_id,t.facility_id)";

	 return DB::statement($opdAttendance);
	 }

	 public static function createViewOpdAttendaces(){

        $opdAttendance="CREATE OR REPLACE VIEW vw_opd_attendaces AS(
        SELECT t.date_attended as date_attended,t.facility_id,
(SELECT COUNT(gender) FROM `tbl_patients` INNER JOIN `tbl_accounts_numbers` 
	ON `tbl_accounts_numbers`.patient_id = 	`tbl_patients`.id AND `tbl_patients`.gender = 'Female'
        AND TIMESTAMPDIFF(MONTH, `tbl_patients`.dob, date_attended) < 1 WHERE date_attended=t.date_attended) AS female_under_one_month,
(SELECT COUNT(gender) FROM `tbl_patients` INNER JOIN `tbl_accounts_numbers` 
	ON `tbl_accounts_numbers`.patient_id = 	`tbl_patients`.id AND `tbl_patients`.gender = 'Male'
        AND TIMESTAMPDIFF(MONTH, `tbl_patients`.dob, date_attended) < 1 WHERE date_attended=t.date_attended) AS male_under_one_month,
(SELECT COUNT(gender) FROM `tbl_patients` INNER JOIN `tbl_accounts_numbers` 
	ON `tbl_accounts_numbers`.patient_id = 	`tbl_patients`.id
        AND TIMESTAMPDIFF(MONTH, `tbl_patients`.dob, date_attended) < 1 WHERE date_attended=t.date_attended ) AS total_under_one_month,
	
	(SELECT COUNT(gender) FROM `tbl_patients` INNER JOIN `tbl_accounts_numbers` 
	ON `tbl_accounts_numbers`.patient_id = 	`tbl_patients`.id AND `tbl_patients`.gender = 'Female'
        AND TIMESTAMPDIFF(MONTH, `tbl_patients`.dob, date_attended) BETWEEN 1 AND 11 WHERE date_attended=t.date_attended ) AS female_under_one_year,
(SELECT COUNT(gender) FROM `tbl_patients` INNER JOIN `tbl_accounts_numbers` 
	ON `tbl_accounts_numbers`.patient_id = 	`tbl_patients`.id AND `tbl_patients`.gender = 'Male'
        AND TIMESTAMPDIFF(MONTH, `tbl_patients`.dob, date_attended) BETWEEN 1 AND 11 WHERE date_attended=t.date_attended ) AS male_under_one_year,
(SELECT COUNT(gender) FROM `tbl_patients` INNER JOIN `tbl_accounts_numbers` 
	ON `tbl_accounts_numbers`.patient_id = 	`tbl_patients`.id
        AND TIMESTAMPDIFF(MONTH, `tbl_patients`.dob, date_attended) BETWEEN 1 AND 11 WHERE date_attended=t.date_attended) AS total_under_one_year,
	
	(SELECT COUNT(gender) FROM `tbl_patients` INNER JOIN `tbl_accounts_numbers` 
	ON `tbl_accounts_numbers`.patient_id = 	`tbl_patients`.id AND `tbl_patients`.gender = 'Female'
        AND TIMESTAMPDIFF(MONTH, `tbl_patients`.dob, date_attended) BETWEEN 12 AND 59 WHERE date_attended=t.date_attended) AS female_under_five_year,
(SELECT COUNT(gender) FROM `tbl_patients` INNER JOIN `tbl_accounts_numbers` 
	ON `tbl_accounts_numbers`.patient_id = 	`tbl_patients`.id AND `tbl_patients`.gender = 'Male'
        AND TIMESTAMPDIFF(MONTH, `tbl_patients`.dob, date_attended) BETWEEN 12 AND 59 WHERE date_attended=t.date_attended) AS male_under_five_year,
	
(SELECT COUNT(gender) FROM `tbl_patients` INNER JOIN `tbl_accounts_numbers` 
	ON `tbl_accounts_numbers`.patient_id = 	`tbl_patients`.id
        AND TIMESTAMPDIFF(MONTH, `tbl_patients`.dob, date_attended) BETWEEN 12 AND 59 WHERE date_attended=t.date_attended) AS total_under_five_year,
	
	(SELECT COUNT(gender) FROM `tbl_patients` INNER JOIN `tbl_accounts_numbers` 
	ON `tbl_accounts_numbers`.patient_id = 	`tbl_patients`.id AND `tbl_patients`.gender = 'Female'
        AND TIMESTAMPDIFF(MONTH, `tbl_patients`.dob, date_attended) BETWEEN 60 AND 719 WHERE date_attended=t.date_attended) AS female_above_five_under_sixty_year,
	
(SELECT COUNT(gender) FROM `tbl_patients` INNER JOIN `tbl_accounts_numbers` 
	ON `tbl_accounts_numbers`.patient_id = 	`tbl_patients`.id AND `tbl_patients`.gender = 'Male'
        AND TIMESTAMPDIFF(MONTH, `tbl_patients`.dob, date_attended) BETWEEN 60 AND 719 WHERE date_attended=t.date_attended) AS male_above_five_under_sixty,
	
(SELECT COUNT(gender) FROM `tbl_patients` INNER JOIN `tbl_accounts_numbers` 
	ON `tbl_accounts_numbers`.patient_id = 	`tbl_patients`.id
        AND TIMESTAMPDIFF(MONTH, `tbl_patients`.dob, date_attended) BETWEEN 60 AND 719 WHERE date_attended=t.date_attended) AS total_above_five_under_sixty,
	
	(SELECT COUNT(gender) FROM `tbl_patients` INNER JOIN `tbl_accounts_numbers` 
	ON `tbl_accounts_numbers`.patient_id = 	`tbl_patients`.id AND `tbl_patients`.gender = 'Female'
        AND TIMESTAMPDIFF(MONTH, `tbl_patients`.dob, date_attended) >=720 WHERE date_attended=t.date_attended) AS female_above_sixty,
	
(SELECT COUNT(gender) FROM `tbl_patients` INNER JOIN `tbl_accounts_numbers` 
	ON `tbl_accounts_numbers`.patient_id = 	`tbl_patients`.id AND `tbl_patients`.gender = 'Male'
        AND TIMESTAMPDIFF(MONTH, `tbl_patients`.dob, date_attended) >=720 WHERE date_attended=t.date_attended) AS male_above_sixty,
	
(SELECT COUNT(gender) FROM `tbl_patients` INNER JOIN `tbl_accounts_numbers` 
	ON `tbl_accounts_numbers`.patient_id = 	`tbl_patients`.id
        AND TIMESTAMPDIFF(MONTH, `tbl_patients`.dob, date_attended) >=720 WHERE date_attended=t.date_attended) AS total_above_sixty,
	(SELECT COUNT(gender) FROM `tbl_patients` INNER JOIN `tbl_accounts_numbers` 
	ON `tbl_accounts_numbers`.patient_id = 	`tbl_patients`.id AND `tbl_patients`.gender = 'Female' WHERE date_attended=t.date_attended) AS grand_total_female,
	
	(SELECT COUNT(gender) FROM `tbl_patients` INNER JOIN `tbl_accounts_numbers` 
	ON `tbl_accounts_numbers`.patient_id = 	`tbl_patients`.id AND `tbl_patients`.gender = 'Male' WHERE date_attended=t.date_attended) AS grand_total_male,
	
	(SELECT COUNT(gender) FROM `tbl_patients` INNER JOIN `tbl_accounts_numbers` 
	ON `tbl_accounts_numbers`.patient_id = 	`tbl_patients`.id WHERE date_attended=t.date_attended ) AS grand_total
	FROM `tbl_accounts_numbers` AS t GROUP BY date_attended,t.facility_id)";
    
	 return DB::statement($opdAttendance);
	 }
	 
	 
public static function createViewNewOpdAttendaces(){
		$registerTables = "
			DROP VIEW IF EXISTS `vw_opd_newattendance`;
			DROP VIEW IF EXISTS `vw_opd_reattendance`;
			DROP TABLE IF EXISTS `tbl_opd_diseases_register`;
			DROP TABLE IF EXISTS `tbl_outgoing_referral_register`;
			
			CREATE TABLE `tbl_outgoing_referral_register` (
			  `facility_id` int(11) NOT NULL,
			  `date` date NOT NULL,
			  `male_under_one_month` int(11) DEFAULT '0',
			  `female_under_one_month` int(11) DEFAULT '0',
			  `total_under_one_month` int(11) DEFAULT '0',
			  `male_under_one_year` int(11) DEFAULT '0',
			  `female_under_one_year` int(11) DEFAULT '0',
			  `total_under_one_year` int(11) DEFAULT '0',
			  `male_under_five_year` int(11) DEFAULT '0',
			  `female_under_five_year` int(11) DEFAULT '0',
			  `total_under_five_year` int(11) DEFAULT '0',
			  `male_above_five_under_sixty` int(11) DEFAULT '0',
			  `female_above_five_under_sixty` int(11) DEFAULT '0',
			  `total_above_five_under_sixty` int(11) DEFAULT '0',
			  `male_above_sixty` int(11) DEFAULT '0',
			  `female_above_sixty` int(11) DEFAULT '0',
			  `total_above_sixty` int(11) DEFAULT '0',
			  `total_male` int(11) DEFAULT '0',
			  `total_female` int(11) DEFAULT '0',
			  `grand_total` int(11) DEFAULT '0'
			) ENGINE=InnoDB DEFAULT CHARSET=latin1;

			CREATE TABLE `tbl_opd_diseases_register` (
			  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
			  `diagnosis_id` int(11) DEFAULT NULL,
			  `facility_id` int(11) not NULL,
			  `date` date not NULL,
			  `male_under_one_month` int(11) DEFAULT '0',
			  `female_under_one_month` int(11) DEFAULT '0',
			  `total_under_one_month` int(11) DEFAULT '0',
			  `male_under_one_year` int(11) DEFAULT '0',
			  `female_under_one_year` int(11) DEFAULT '0',
			  `total_under_one_year` int(11) DEFAULT '0',
			  `male_under_five_year` int(11) DEFAULT '0',
			  `female_under_five_year` int(11) DEFAULT '0',
			  `total_under_five_year` int(11) DEFAULT '0',
			  `male_above_five_under_sixty` int(11) DEFAULT '0',
			  `female_above_five_under_sixty` int(11) DEFAULT '0',
			  `total_above_five_under_sixty` int(11) DEFAULT '0',
			  `male_above_sixty` int(11) DEFAULT '0',
			  `female_above_sixty` int(11) DEFAULT '0',
			  `total_above_sixty` int(11) DEFAULT '0',
			  `total_male` int(11) DEFAULT '0',
			  `total_female` int(11) DEFAULT '0',
			  `grand_total` int(11) DEFAULT '0'
			) ENGINE=InnoDB DEFAULT CHARSET=latin1;


			DROP TABLE IF EXISTS `tbl_newattendance_register`;
			CREATE TABLE `tbl_newattendance_register` (
			  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
			  `facility_id` int(11) not NULL,
			  `clinic_id` int(11) not NULL,
			  `date` date not NULL,
			  `male_under_one_month` int(11) DEFAULT '0',
			  `female_under_one_month` int(11) DEFAULT '0',
			  `total_under_one_month` int(11) DEFAULT '0',
			  `male_under_one_year` int(11) DEFAULT '0',
			  `female_under_one_year` int(11) DEFAULT '0',
			  `total_under_one_year` int(11) DEFAULT '0',
			  `male_under_five_year` int(11) DEFAULT '0',
			  `female_under_five_year` int(11) DEFAULT '0',
			  `total_under_five_year` int(11) DEFAULT '0',
			  `male_above_five_under_sixty` int(11) DEFAULT '0',
			  `female_above_five_under_sixty` int(11) DEFAULT '0',
			  `total_above_five_under_sixty` int(11) DEFAULT '0',
			  `male_above_sixty` int(11) DEFAULT '0',
			  `female_above_sixty` int(11) DEFAULT '0',
			  `total_above_sixty` int(11) DEFAULT '0',
			  `total_male` int(11) DEFAULT '0',
			  `total_female` int(11) DEFAULT '0',
			  `grand_total` int(11) DEFAULT '0'
			) ENGINE=InnoDB DEFAULT CHARSET=latin1;


			DROP TABLE IF EXISTS `tbl_reattendance_register`;
			CREATE TABLE `tbl_reattendance_register` (
			  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
			  `facility_id` int(11) not NULL,
			  `clinic_id` int(11) not NULL,
			  `date` date not NULL,
			  `male_under_one_month` int(11) DEFAULT '0',
			  `female_under_one_month` int(11) DEFAULT '0',
			  `total_under_one_month` int(11) DEFAULT '0',
			  `male_under_one_year` int(11) DEFAULT '0',
			  `female_under_one_year` int(11) DEFAULT '0',
			  `total_under_one_year` int(11) DEFAULT '0',
			  `male_under_five_year` int(11) DEFAULT '0',
			  `female_under_five_year` int(11) DEFAULT '0',
			  `total_under_five_year` int(11) DEFAULT '0',
			  `male_above_five_under_sixty` int(11) DEFAULT '0',
			  `female_above_five_under_sixty` int(11) DEFAULT '0',
			  `total_above_five_under_sixty` int(11) DEFAULT '0',
			  `male_above_sixty` int(11) DEFAULT '0',
			  `female_above_sixty` int(11) DEFAULT '0',
			  `total_above_sixty` int(11) DEFAULT '0',
			  `total_male` int(11) DEFAULT '0',
			  `total_female` int(11) DEFAULT '0',
			  `grand_total` int(11) DEFAULT '0'
			) ENGINE=InnoDB DEFAULT CHARSET=latin1;



			DROP TABLE IF EXISTS `tbl_ipd_diseases_register`;
			CREATE TABLE `tbl_ipd_diseases_register` (
			  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
			  `diagnosis_id` int(11) DEFAULT NULL,
			  `facility_id` int(11) not NULL,
			  `date` date not NULL,
			  `male_under_one_month` int(11) DEFAULT '0',
			  `female_under_one_month` int(11) DEFAULT '0',
			  `total_under_one_month` int(11) DEFAULT '0',
			  `male_under_one_year` int(11) DEFAULT '0',
			  `female_under_one_year` int(11) DEFAULT '0',
			  `total_under_one_year` int(11) DEFAULT '0',
			  `male_under_five_year` int(11) DEFAULT '0',
			  `female_under_five_year` int(11) DEFAULT '0',
			  `total_under_five_year` int(11) DEFAULT '0',
			  `male_above_five_under_sixty` int(11) DEFAULT '0',
			  `female_above_five_under_sixty` int(11) DEFAULT '0',
			  `total_above_five_under_sixty` int(11) DEFAULT '0',
			  `male_above_sixty` int(11) DEFAULT '0',
			  `female_above_sixty` int(11) DEFAULT '0',
			  `total_above_sixty` int(11) DEFAULT '0',
			  `total_male` int(11) DEFAULT '0',
			  `total_female` int(11) DEFAULT '0',
			  `grand_total` int(11) DEFAULT '0'
			) ENGINE=InnoDB DEFAULT CHARSET=latin1;


			DROP TABLE IF EXISTS `tbl_admission_register`;
			CREATE TABLE `tbl_admission_register` (
			  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
			  `facility_id` int(11) not NULL,
			  `date` date not NULL,
			  `male_under_one_month` int(11) DEFAULT '0',
			  `female_under_one_month` int(11) DEFAULT '0',
			  `total_under_one_month` int(11) DEFAULT '0',
			  `male_under_one_year` int(11) DEFAULT '0',
			  `female_under_one_year` int(11) DEFAULT '0',
			  `total_under_one_year` int(11) DEFAULT '0',
			  `male_under_five_year` int(11) DEFAULT '0',
			  `female_under_five_year` int(11) DEFAULT '0',
			  `total_under_five_year` int(11) DEFAULT '0',
			  `male_above_five_under_sixty` int(11) DEFAULT '0',
			  `female_above_five_under_sixty` int(11) DEFAULT '0',
			  `total_above_five_under_sixty` int(11) DEFAULT '0',
			  `male_above_sixty` int(11) DEFAULT '0',
			  `female_above_sixty` int(11) DEFAULT '0',
			  `total_above_sixty` int(11) DEFAULT '0',
			  `total_male` int(11) DEFAULT '0',
			  `total_female` int(11) DEFAULT '0',
			  `grand_total` int(11) DEFAULT '0'
			) ENGINE=InnoDB DEFAULT CHARSET=latin1;


			DROP TABLE IF EXISTS `tbl_clinic_attendance_register`;
			CREATE TABLE `tbl_clinic_attendance_register` (
			  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
			  `clinic_id` int(11) DEFAULT NULL,
			  `facility_id` int(11) not NULL,
			  `date` date not NULL,
			  `male_under_one_month` int(11) DEFAULT '0',
			  `female_under_one_month` int(11) DEFAULT '0',
			  `total_under_one_month` int(11) DEFAULT '0',
			  `male_under_one_year` int(11) DEFAULT '0',
			  `female_under_one_year` int(11) DEFAULT '0',
			  `total_under_one_year` int(11) DEFAULT '0',
			  `male_under_five_year` int(11) DEFAULT '0',
			  `female_under_five_year` int(11) DEFAULT '0',
			  `total_under_five_year` int(11) DEFAULT '0',
			  `male_above_five_under_sixty` int(11) DEFAULT '0',
			  `female_above_five_under_sixty` int(11) DEFAULT '0',
			  `total_above_five_under_sixty` int(11) DEFAULT '0',
			  `male_above_sixty` int(11) DEFAULT '0',
			  `female_above_sixty` int(11) DEFAULT '0',
			  `total_above_sixty` int(11) DEFAULT '0',
			  `total_male` int(11) DEFAULT '0',
			  `total_female` int(11) DEFAULT '0',
			  `grand_total` int(11) DEFAULT '0'
			) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
		return DB::statement($registerTables);
		
        $opdNewAttendance="CREATE OR REPLACE VIEW vw_newattendance  AS (SELECT tbl_newattendance_register.facility_id, tbl_newattendance_register.clinic_id, tbl_newattendance_register.date, ifnull(male_under_one_month,0) as male_under_one_month,ifnull(female_under_one_month,0) as female_under_one_month, ifnull(total_under_one_month,0) as total_under_one_month,ifnull(male_under_one_year,0) as male_under_one_year,ifnull(female_under_one_year,0) as female_under_one_year,ifnull(total_under_one_year,0) as total_under_one_year,ifnull(male_under_five_year,0) as male_under_five_year,ifnull(female_under_five_year,0) as female_under_five_year,ifnull(total_under_five_year,0) as total_under_five_year,ifnull(male_above_five_under_sixty,0) as male_above_five_under_sixty,ifnull(female_above_five_under_sixty,0) as female_above_five_under_sixty,ifnull(total_above_five_under_sixty,0) as total_above_five_under_sixty,ifnull(male_above_sixty,0) as male_above_sixty,ifnull(female_above_sixty,0) as female_above_sixty,ifnull(total_above_sixty,0) as total_above_sixty,ifnull(total_male,0) as total_male,ifnull(total_female,0) as total_female,ifnull(grand_total,0) as grand_total FROM tbl_newattendance_register)";

	 return DB::statement($opdNewAttendance);
	 }



/**
marudio mtuha ibadilishwe jina la view
public static function createViewMarudioOpdAttendaces(){

			$opdmarudioAttendance="CREATE OR REPLACE VIEW vw_reattendance AS(SELECT tbl_reattendance_register.facility_id, tbl_reattendance_register.clinic_id, tbl_reattendance_register.date, ifnull(male_under_one_month,0) as male_under_one_month,ifnull(female_under_one_month,0) as female_under_one_month, ifnull(total_under_one_month,0) as total_under_one_month,ifnull(male_under_one_year,0) as male_under_one_year,ifnull(female_under_one_year,0) as female_under_one_year,ifnull(total_under_one_year,0) as total_under_one_year,ifnull(male_under_five_year,0) as male_under_five_year,ifnull(female_under_five_year,0) as female_under_five_year,ifnull(total_under_five_year,0) as total_under_five_year,ifnull(male_above_five_under_sixty,0) as male_above_five_under_sixty,ifnull(female_above_five_under_sixty,0) as female_above_five_under_sixty,ifnull(total_above_five_under_sixty,0) as total_above_five_under_sixty,ifnull(male_above_sixty,0) as male_above_sixty,ifnull(female_above_sixty,0) as female_above_sixty,ifnull(total_above_sixty,0) as total_above_sixty,ifnull(total_male,0) as total_male,ifnull(total_female,0) as total_female,ifnull(grand_total,0) as grand_total FROM tbl_reattendance_register)";

		 return DB::statement($opdmarudioAttendance);
		
	 }
**/

	 public static function createViewClinicAttendances(){

		$clinicattendance="CREATE OR REPLACE VIEW vw_clinic_attendance AS(SELECT tbl_clinic_attendance_register.facility_id, tbl_clinic_attendance_register.clinic_id, tbl_clinic_attendance_register.date, ifnull(male_under_one_month,0) as male_under_one_month,ifnull(female_under_one_month,0) as female_under_one_month, ifnull(total_under_one_month,0) as total_under_one_month,ifnull(male_under_one_year,0) as male_under_one_year,ifnull(female_under_one_year,0) as female_under_one_year,ifnull(total_under_one_year,0) as total_under_one_year,ifnull(male_under_five_year,0) as male_under_five_year,ifnull(female_under_five_year,0) as female_under_five_year,ifnull(total_under_five_year,0) as total_under_five_year,ifnull(male_above_five_under_sixty,0) as male_above_five_under_sixty,ifnull(female_above_five_under_sixty,0) as female_above_five_under_sixty,ifnull(total_above_five_under_sixty,0) as total_above_five_under_sixty,ifnull(male_above_sixty,0) as male_above_sixty,ifnull(female_above_sixty,0) as female_above_sixty,ifnull(total_above_sixty,0) as total_above_sixty,ifnull(total_male,0) as total_male,ifnull(total_female,0) as total_female,ifnull(grand_total,0) as grand_total FROM tbl_clinic_attendance_register)";

         return DB::statement($clinicattendance);
     }


public static  function getAdmittedPatientsIPd(){
    $getAdmitted="";

    //return DB::statement($getAdmitted);
}

public static function createViewDiagnosisOpdAttendaces(){

        $opdDiagnosis="CREATE OR REPLACE VIEW vw_opd_diagnosises AS(SELECT tbl_opd_diseases_register.diagnosis_id,tbl_opd_diseases_register.facility_id, tbl_opd_diseases_register.date, t1.description,ifnull(sum(male_under_one_month),0) as male_under_one_month,ifnull(sum(female_under_one_month),0) as female_under_one_month, ifnull(sum(total_under_one_month),0) as total_under_one_month,ifnull(sum(male_under_one_year),0) as male_under_one_year,ifnull(sum(female_under_one_year),0) as female_under_one_year,ifnull(sum(total_under_one_year),0) as total_under_one_year,ifnull(sum(male_under_five_year),0) as male_under_five_year,ifnull(sum(female_under_five_year),0) as female_under_five_year,ifnull(sum(total_under_five_year),0) as total_under_five_year,ifnull(sum(male_above_five_under_sixty),0) as male_above_five_under_sixty,ifnull(sum(female_above_five_under_sixty),0) as female_above_five_under_sixty,ifnull(sum(total_above_five_under_sixty),0) as total_above_five_under_sixty,ifnull(sum(male_above_sixty),0) as male_above_sixty,ifnull(sum(female_above_sixty),0) as female_above_sixty,ifnull(sum(total_above_sixty),0) as total_above_sixty,ifnull(sum(total_male),0) as total_male,ifnull(sum(total_female),0) as total_female,ifnull(sum(grand_total),0) as grand_total FROM (tbl_diagnosis_descriptions t1 JOIN tbl_mtuha_diagnosis_filters t2 ON t1.id=t2.diagnosis_id) LEFT JOIN tbl_opd_diseases_register on t1.id = tbl_opd_diseases_register.diagnosis_id group by tbl_opd_diseases_register.facility_id, tbl_opd_diseases_register.diagnosis_id, tbl_opd_diseases_register.date ORDER BY t2.id)";

	 return DB::statement($opdDiagnosis);
	 }

	 public static function  createViewDoctors(){
      $doctorsPerfomances="CREATE OR REPLACE VIEW vw_doctor_perfomances AS(
      SELECT t1.facility_id,t1.user_id AS doctor_id,t1.created_at AS time_treated,DATE(t1.created_at) AS date_clerked,
       t2.name AS doctor_name,t3.prof_name,
       (SELECT COUNT(visit_date_id) FROM `tbl_history_examinations` INNER JOIN `tbl_accounts_numbers` ON `tbl_accounts_numbers`.id = `tbl_history_examinations`.visit_date_id WHERE tbl_history_examinations.user_id=t1.user_id AND tbl_history_examinations.created_at=t1.created_at) AS total_clerked FROM tbl_history_examinations t1 INNER JOIN users t2 ON t1.user_id=t2.id INNER JOIN tbl_proffesionals t3 ON t3.id=t2.user_type GROUP BY t1.user_id,t1.facility_id,t1.created_at,t2.name,t3.prof_name ORDER BY t1.created_at)";

          return DB::statement($doctorsPerfomances);
     }


	 public static function createViewIpdDiagnosis(){

         $ipdAdmittedDiagnosis="CREATE OR REPLACE VIEW vw_ipd_diagnosises AS(SELECT tbl_ipd_diseases_register.diagnosis_id,tbl_ipd_diseases_register.facility_id, tbl_ipd_diseases_register.date, t1.description,ifnull(sum(male_under_one_month),0) as male_under_one_month,ifnull(sum(female_under_one_month),0) as female_under_one_month, ifnull(sum(total_under_one_month),0) as total_under_one_month,ifnull(sum(male_under_one_year),0) as male_under_one_year,ifnull(sum(female_under_one_year),0) as female_under_one_year,ifnull(sum(total_under_one_year),0) as total_under_one_year,ifnull(sum(male_under_five_year),0) as male_under_five_year,ifnull(sum(female_under_five_year),0) as female_under_five_year,ifnull(sum(total_under_five_year),0) as total_under_five_year,ifnull(sum(male_above_five_under_sixty),0) as male_above_five_under_sixty,ifnull(sum(female_above_five_under_sixty),0) as female_above_five_under_sixty,ifnull(sum(total_above_five_under_sixty),0) as total_above_five_under_sixty,ifnull(sum(male_above_sixty),0) as male_above_sixty,ifnull(sum(female_above_sixty),0) as female_above_sixty,ifnull(sum(total_above_sixty),0) as total_above_sixty,ifnull(sum(total_male),0) as total_male,ifnull(sum(total_female),0) as total_female,ifnull(sum(grand_total),0) as grand_total FROM (tbl_diagnosis_descriptions t1 JOIN tbl_mtuha_diagnosis_filters t2 ON t1.id=t2.diagnosis_id) LEFT JOIN tbl_ipd_diseases_register on t1.id = tbl_ipd_diseases_register.diagnosis_id group by tbl_ipd_diseases_register.facility_id, tbl_ipd_diseases_register.diagnosis_id, tbl_ipd_diseases_register.date ORDER BY t2.id)";
         return DB::statement($ipdAdmittedDiagnosis);
     }

public static function createViewIpdAdmitted(){

        $ipdAdmitted="CREATE OR REPLACE VIEW vw_admission AS(SELECT tbl_admission_register.facility_id, tbl_admission_register.date, ifnull(male_under_one_month,0) as male_under_one_month,ifnull(female_under_one_month,0) as female_under_one_month, ifnull(total_under_one_month,0) as total_under_one_month,ifnull(male_under_one_year,0) as male_under_one_year,ifnull(female_under_one_year,0) as female_under_one_year,ifnull(total_under_one_year,0) as total_under_one_year,ifnull(male_under_five_year,0) as male_under_five_year,ifnull(female_under_five_year,0) as female_under_five_year,ifnull(total_under_five_year,0) as total_under_five_year,ifnull(male_above_five_under_sixty,0) as male_above_five_under_sixty,ifnull(female_above_five_under_sixty,0) as female_above_five_under_sixty,ifnull(total_above_five_under_sixty,0) as total_above_five_under_sixty,ifnull(male_above_sixty,0) as male_above_sixty,ifnull(female_above_sixty,0) as female_above_sixty,ifnull(total_above_sixty,0) as total_above_sixty,ifnull(total_male,0) as total_male,ifnull(total_female,0) as total_female,ifnull(grand_total,0) as grand_total FROM tbl_admission_register)";

	 return DB::statement($ipdAdmitted);
	 }




    public static function investigationTests(){
        $tests = "CREATE OR REPLACE VIEW `vw_investigations_tests` AS(SELECT
                tbl_items.item_name,
                tbl_items.id AS item_id,
                tbl_item_prices.id AS item_price_id,
                tbl_item_prices.price,
                tbl_item_prices.facility_id,
                tbl_item_prices.sub_category_id AS patient_category_id,
                tbl_sub_departments.id AS sub_dept_id,
                tbl_departments.id AS dept_id,
                tbl_item_type_mappeds.id AS item_type_id,
                tbl_equipments.id AS equipment_id,
                tbl_equipment_statuses.on_off
                FROM tbl_tests 
                INNER JOIN tbl_items ON tbl_tests.item_id=tbl_items.id 
                INNER JOIN tbl_sub_departments ON  tbl_tests.sub_department_id=tbl_sub_departments.id
                INNER JOIN tbl_departments ON tbl_departments.id=tbl_sub_departments.department_id
                INNER JOIN tbl_item_type_mappeds ON tbl_item_type_mappeds.item_id=tbl_items.id
                INNER JOIN tbl_item_prices ON tbl_item_prices.item_id=tbl_items.id
                INNER JOIN tbl_equipments ON tbl_equipments.id=tbl_tests.equipment_id
                INNER JOIN tbl_equipment_statuses ON tbl_equipment_statuses.id=tbl_equipments.equipment_status_id
                
                )";
        return DB::statement($tests);
    }


  public static function labTestsToDoctor(){
        $tests = "CREATE OR REPLACE VIEW `vw_labtests_to_doctors` AS(SELECT
                tbl_items.item_name,
                tbl_items.id AS item_id,
                tbl_item_prices.id AS item_price_id,
                tbl_item_prices.price,
                tbl_item_prices.facility_id,
                tbl_item_prices.sub_category_id AS patient_category_id,
                tbl_sub_departments.id AS sub_dept_id,
                tbl_departments.id AS dept_id,
                tbl_item_type_mappeds.id AS item_type_id,
                tbl_equipments.id AS equipment_id,
                tbl_equipment_statuses.on_off
                FROM tbl_testspanels 
                INNER JOIN tbl_items ON tbl_testspanels.item_id=tbl_items.id 
                INNER JOIN tbl_equipments ON tbl_equipments.id=tbl_testspanels.equipment_id
                INNER JOIN tbl_sub_departments ON  tbl_equipments.sub_department_id=tbl_sub_departments.id
                INNER JOIN tbl_departments ON tbl_departments.id=tbl_sub_departments.department_id
                INNER JOIN tbl_item_type_mappeds ON tbl_item_type_mappeds.item_id=tbl_items.id
                INNER JOIN tbl_item_prices ON tbl_item_prices.item_id=tbl_items.id
                INNER JOIN tbl_equipment_statuses ON tbl_equipment_statuses.id=tbl_equipments.equipment_status_id
                
                )";
        return DB::statement($tests);
    }
    public static function investigationResults(){
        $results = "CREATE OR REPLACE VIEW `vw_investigation_results` AS (
		SELECT t1.item_id,
		t1.description,
		t1.attached_image,
		t1.panel,
		t1.remarks,
		t2.sample_no,
		t6.name,
		t6.mobile_number,
		t4.id AS account_id,
		t4.id AS visit_date_id,
		t4.patient_id,
		t4.facility_id,
		t7.first_name,
		t7.middle_name,
		t7.last_name,
		t7.medical_record_number,
		t7.dob,
        CASE WHEN TIMESTAMPDIFF(YEAR,dob, CURRENT_DATE) 

<> 0 THEN CONCAT(TIMESTAMPDIFF(YEAR,dob, CURRENT_DATE), 

' Years') ELSE CASE WHEN TIMESTAMPDIFF(MONTH,dob, 

CURRENT_DATE) <> 0 THEN CONCAT(TIMESTAMPDIFF(MONTH, 

dob, CURRENT_DATE), ' Months') ELSE CONCAT

(TIMESTAMPDIFF(DAY, dob, CURRENT_DATE), ' Days') END 

END
AS age,
        (TIMESTAMPDIFF(YEAR,t7.dob, CURRENT_DATE)) AS 

umri,
        t7.gender,
        t4.date_attended AS visit_date,
        t8.main_category_id,
		t8.bill_id,
t1.confirmation_status,
t5.item_name,t5.dept_id,
t1.created_at,
t4.date_attended
FROM tbl_results t1
INNER JOIN tbl_requests t3 ON t3.id = t1.order_id
INNER JOIN tbl_orders t2 ON t2.order_id = t3.id and 

t2.test_id=t1.item_id
INNER JOIN tbl_accounts_numbers t4 ON t4.id = 

t3.visit_date_id
INNER JOIN tbl_items t5 ON t5.id = t1.item_id
INNER JOIN users t6 ON t6.id = t1.verify_user
INNER JOIN tbl_patients t7 ON t7.id = t3.patient_id
INNER JOIN tbl_bills_categories t8 ON t4.id = 

t8.account_id where t1.confirmation_status=1)";
        return DB::statement($results);
    }
    public static function investigationLists(){
        $results = "CREATE OR REPLACE VIEW `vw_investigation_lists` AS ( SELECT 
        t8.id AS patient_id,
        t8.first_name,
        t8.middle_name,
        t8.last_name,
        t8.medical_record_number,
        t8.dob,
        CASE WHEN TIMESTAMPDIFF(YEAR,dob, CURRENT_DATE) <> 0 THEN CONCAT(TIMESTAMPDIFF(YEAR,dob, CURRENT_DATE), ' Years') ELSE CASE WHEN TIMESTAMPDIFF(MONTH,dob, CURRENT_DATE) <> 0 THEN CONCAT(TIMESTAMPDIFF(MONTH, dob, CURRENT_DATE), ' Months') ELSE CONCAT(TIMESTAMPDIFF(DAY, dob, CURRENT_DATE), ' Days') END END
AS age,
        t8.gender,
        t3.visit_date_id,
        t7.date_attended,
        t7.id AS account_id,
        t4.item_name,
        t1.description,
        t1.unit,
        t1.order_id,
        t1.attached_image,
        t1.post_time,
        t1.verify_time,
        t1.created_at,
        t5.facility_id,
        t6.id AS dept_id,
        t6.department_name,
        t9.main_category_id,
        t9.bill_id
        FROM
        tbl_results t1, tbl_orders t2, tbl_requests t3, tbl_items t4,users t5,tbl_departments t6,tbl_accounts_numbers t7,tbl_patients t8,tbl_bills_categories t9
        WHERE (timestampdiff(hour,t1.updated_at,CURRENT_TIMESTAMP))<= 24
        AND t3.id=t2.order_id
        AND t2.id = t1.order_id
        AND t7.id = t3.visit_date_id
        AND t7.id = t9.account_id
        AND t4.id = t2.test_id
        AND t4.dept_id = t6.id
        AND t5.id = t1.verify_user
        AND t8.id = t7.patient_id
        AND t1.confirmation_status = 1        
        )";
        return DB::statement($results);
    }
    public static function labPanels()
    {
        $panel ="CREATE OR REPLACE VIEW `vw_labPanels` AS(SELECT
                tbl_panels.panel_name as item_name,
               	tbl_item_type_mappeds.id AS item_type_id,
               	tbl_item_prices.id AS item_price_id,			
               	tbl_items.id AS item_id,			
               	tbl_item_prices.price,			
               	tbl_item_prices.facility_id,			
               	tbl_departments.id AS dept_id,			
               	tbl_item_prices.sub_category_id	AS patient_category_id,
               	tbl_equipments.sub_department_id AS sub_dept_id,		
               	tbl_equipment_statuses.on_off		
                FROM tbl_panels
                INNER JOIN tbl_items ON tbl_items.id=tbl_panels.item_id
                INNER JOIN tbl_item_type_mappeds ON tbl_items.id=tbl_item_type_mappeds.item_id
                INNER JOIN tbl_item_prices ON tbl_items.id=tbl_item_prices.item_id
                INNER JOIN tbl_equipments ON tbl_equipments.id=tbl_panels.equipment_id
                INNER JOIN tbl_equipment_statuses ON tbl_equipment_statuses.id=tbl_equipments.equipment_status_id
                INNER JOIN tbl_departments ON tbl_departments.id=tbl_items.dept_id
				
         )"; return DB::statement($panel);

    }
 public  static function continuationNotes(){
        $cont =" CREATE OR REPLACE VIEW `vw_continuation_notes` AS ( SELECT
        t1.notes,
        t1.patient_id,
        t1.created_at,
        t1.notes_type,
        t2.name,
        t3.prof_name,
        t4.facility_name
        FROM tbl_continuation_notes t1,users t2,tbl_proffesionals t3,tbl_facilities t4
        WHERE
        t2.id = t1.user_id
        AND t4.id = t1.facility_id
        AND t3.id = t2.user_type
        )";
        return DB::statement($cont);
    }

        public static function diagnosis()
        {
            $diag ="CREATE OR REPLACE VIEW `vw_prev_diagnosis` AS (SELECT
            t3.date_attended,
            t2.status,
			t1.id,
            t4.description,
            t1.admission_id,
			t4.code AS DiseaseCode,
            t3.patient_id,
            t3.facility_id,
            t3.id AS visit_date_id        
            FROM tbl_diagnoses t1,tbl_diagnosis_details t2,tbl_accounts_numbers t3,tbl_diagnosis_descriptions t4
            WHERE
            t3.id = t1.visit_date_id
            AND t1.id = t2.diagnosis_id
            AND t4.id = t2.diagnosis_description_id
            )";
            return DB::statement($diag);
        }

    public static function familyHistory()
    {
        $family = "CREATE OR REPLACE VIEW `vw_family_history` AS( SELECT 
            t1.id AS visit_date_id,
            t1.patient_id,
            t1.date_attended,
            t3.chronic_illness,
            t3.substance_abuse,
            t3.adoption,
            t3.others
            FROM tbl_accounts_numbers t1,tbl_family_histories t2,tbl_family_social_histories t3
            WHERE
            t1.id = t2.visit_date_id
            AND t2.id = t3.family_history_id
            )";
        return DB::statement($family);
    }
    public static function birthHistory()
    {
        $birth = "CREATE OR REPLACE VIEW `vw_birth_history` AS( SELECT 
            t1.id AS visit_date_id,
            t1.patient_id,
            t1.date_attended,
            t3.antenatal,
            t3.natal,
            t3.post_natal,
            t3.growth,
            t3.nutrition,
            t3.development
            FROM tbl_accounts_numbers t1,tbl_birth_histories t2, tbl_child_birth_histories t3
            WHERE
            t1.id = t2.visit_date_id
            AND t2.id = t3.birth_history_id
            )";
        return DB::statement($birth);
    }
    public static function historyExaminations()
    {
        $complaints = "CREATE OR REPLACE VIEW `vw_history_examinations` AS( SELECT 
            t1.id AS visit_date_id,
            t1.patient_id,
            t1.date_attended,
            t3.description,
            t3.duration,
            t3.duration_unit,
            t3.other_complaints,
            t3.hpi,
            t3.status
            FROM tbl_accounts_numbers t1,tbl_history_examinations t2, tbl_complaints t3
            WHERE
            t1.id = t2.visit_date_id
            AND t2.id = t3.history_exam_id
            )";
        return DB::statement($complaints);
    }
    public static function obs()
    {
        /*$obs = "CREATE OR REPLACE VIEW `vw_obs_gyn` AS( SELECT 
            t1.id AS visit_date_id,
            t1.patient_id,
            t1.date_attended,
            t3.*,
            FROM tbl_accounts_numbers t1,tbl_obs_gyns t2, tbl_obs_gyn_records t3
            WHERE
            t1.id = t2.visit_date_id
            AND t2.id = t3.obs_gyn_id
            )";
        return DB::statement($obs);*/
    }
    public static function ros()
    {
        $ros = "CREATE OR REPLACE VIEW `vw_review_of_systems` AS( SELECT 
            t1.id AS visit_date_id,
            t1.patient_id,
            t1.date_attended,
            t3.status,
            t3.review_summary,
            t4.name
            FROM tbl_accounts_numbers t1,tbl_review_systems t2, tbl_review_of_systems t3,tbl_body_systems t4
            WHERE
            t1.id = t2.visit_date_id
            AND t2.id = t3.review_system_id
            AND t4.id = t3.system_id
            )";
        return DB::statement($ros);
    }
    public static function physicalExaminations()
    {
        $ros = "CREATE OR REPLACE VIEW `vw_physical_examinations` AS( SELECT 
            t1.id AS visit_date_id,
            t1.patient_id,
            t1.date_attended,
            t3.observation,
            t3.gen_examination,
            t3.summary_examination,
            t3.category,
            t3.local_examination,
            t3.system
            FROM tbl_accounts_numbers t1,tbl_physical_examinations t2, tbl_physical_examination_records t3
            WHERE
            t1.id = t2.visit_date_id
            AND t2.id = t3.physical_examination_id
            )";
        return DB::statement($ros);
    }

        public static function admittedPatients()
        {
        $admitted = "CREATE OR REPLACE VIEW `vw_admitted_patients` AS (SELECT 
        t1.id AS patient_id,
        t1.first_name,
        t1.middle_name,
        t1.last_name,
        t1.medical_record_number,
        t1.dob,
        t1.gender,
        t2.account_number,
        t2.id AS account_id,
        t2.date_attended AS visit_date,
        t3.admission_date,
        t3.id AS admission_id,
        t3.admission_status_id,
        t4.main_category_id,
        t4.bill_id,
        t5.ward_id,
        t2.facility_id
        FROM tbl_patients t1,tbl_accounts_numbers t2,tbl_admissions t3,tbl_bills_categories t4, tbl_instructions t5
       WHERE
            t1.id=t2.patient_id 
        AND t1.id=t3.patient_id
        AND t1.id=t4.patient_id
        AND t3.id=t5.admission_id
        AND t3.admission_status_id = 2
        )";
            return DB::statement($admitted);
        }

        public static function icu()
        {
            $icu = "CREATE OR REPLACE VIEW `vw_icu_patients` AS(SELECT
            t1.id AS patient_id,
        t1.first_name,
        t1.middle_name,
        t1.last_name,
        t1.medical_record_number,
        t1.dob,
        t1.gender,
        t2.account_number,
        t2.id AS account_id,
        t2.facility_id,
        t2.date_attended AS visit_date,
        t4.main_category_id,
        t4.bill_id,
        t6.Body_temperature,
        t6.Oxygen_saturation,
        t6.Body_weight,
        t6.height_length,
        t6.Systolic_pressure,
        t6.Diastolic_pressure,
        t6.Pulse_rate
        FROM tbl_patients t1,tbl_accounts_numbers t2,tbl_admissions t3,tbl_bills_categories t4,tbl_icu_entries t5,
        tbl_vital_signs t6
        WHERE
        t1.id=t2.patient_id 
        AND t1.id=t3.patient_id
        AND t1.id=t6.patient_id
        AND t2.id=t4.account_id
        AND t3.id=t5.admission_id   
         order by t2.date_attended DESC)";
            return DB::statement($icu);
        }

    public static function allergies()
    {
        $allergy = "CREATE OR REPLACE VIEW `vw_allergies` AS(SELECT
             t2.descriptions,
             t2.status,
             t3.date_attended,
             t1.patient_id
             FROM tbl_past_medical_histories t1 INNER JOIN tbl_past_medical_records t2
             ON t1.id = t2.past_medical_history_id
             INNER JOIN tbl_accounts_numbers t3 ON t3.id=t1.visit_date_id
             WHERE t2.status = 'Allergy'
            )";
        return DB::statement($allergy);
    }
		public static function departmentalReports(){
        $departmental = "CREATE OR REPLACE VIEW `vw_departmental_summary` AS 

(SELECT t5.department_name,t1.facility_id,t1.updated_at,t1.updated_at as created_at,t1.id,((price * quantity)-t1.discount) AS resultant_pay    FROM   
        tbl_invoice_lines t1, tbl_item_prices t2,tbl_item_type_mappeds t3,
		tbl_items t4,tbl_departments t5      WHERE t2.id=t1.item_price_id
		AND t1.status_id=2 AND t3.id=t1.item_type_id  AND t4.id=t3.item_id 
         AND t5.id=t4.dept_id)";
        return DB::statement($departmental);
    }
    public static function subDepartmentReports(){
        $subdepartmental = "CREATE OR REPLACE VIEW `vw_sub_department_summary` AS

(SELECT t5.sub_department_name,t4.item_name,t1.facility_id,t1.updated_at,t1.updated_at as created_at,t1.id,((price * quantity)-t1.discount) AS resultant_pay       
 FROM tbl_invoice_lines t1, tbl_item_prices t2,tbl_item_type_mappeds t3,
 tbl_items t4,tbl_sub_departments t5,tbl_item_sub_departments t6
 WHERE t2.id=t1.item_price_id  AND t1.status_id=2  AND t3.id=t1.item_type_id  
 AND t4.id=t3.item_id AND t5.id=t6.sub_dept_id   AND t4.id=t6.item_id )";
        return DB::statement($subdepartmental);
    }


    public static function vw_exemp_sub_department_summary(){
        $subdepartmental = "CREATE OR REPLACE VIEW `vw_exemp_sub_department_summary` AS (SELECT
         t5.sub_department_name,t4.item_name,t1.facility_id,t1.created_at,t1.id,
         ((price * quantity)-t1.discount) AS resultant_pay
         FROM 
         tbl_invoice_lines t1, tbl_item_prices t2,tbl_item_type_mappeds t3,tbl_items t4,tbl_sub_departments t5,
         tbl_item_sub_departments t6,tbl_pay_cat_sub_categories t7
         WHERE t2.id =t1.item_price_id 
         AND t3.id=t1.item_type_id
         AND t4.id=t3.item_id
         AND t5.id=t6.sub_dept_id         
         AND t4.id=t6.item_id         
         AND t1.payment_filter=t7.id  
                AND t7.pay_cat_id=3
         )";
        return DB::statement($subdepartmental);
    }

    //	end of view		


					
	public static function conceptDictionary(){
		            
		            $conceptDictionary="CREATE OR REPLACE VIEW `vw_concept_dictionery` AS ( SELECT
					t1.id AS item_id,
					t1.item_name,
					t2.item_category,
					t2.dose_formulation,
					t2.dispensing_unit,
					t2.sub_item_category
					FROM  tbl_items t1,
					tbl_item_type_mappeds t2
					WHERE 
					t1.id = t2.item_id  
					 )";                     
       
					//var_dump($itemPrices);		
					 return DB::statement($conceptDictionary);
					
	                }


    public static function previousMedications()
    {
        $prevmedics = "CREATE OR REPLACE VIEW `vw_previous_medications` AS (
        SELECT
        tbl_items.id AS item_id,
        tbl_items.item_name,
        tbl_prescriptions.patient_id,
        tbl_prescriptions.quantity,
        tbl_prescriptions.frequency,
        tbl_prescriptions.duration,
        tbl_prescriptions.dose,
        tbl_accounts_numbers.date_attended,
        tbl_prescriptions.visit_id,
        tbl_prescriptions.out_of_stock,
        (timestampdiff(DAY,tbl_prescriptions.start_date,CURRENT_TIMESTAMP)) AS days,
        tbl_prescriptions.start_date,
        tbl_prescriptions.instruction
         FROM tbl_prescriptions 
		 INNER JOIN  tbl_items ON tbl_items.id = tbl_prescriptions.item_id
		 INNER JOIN  tbl_accounts_numbers ON tbl_accounts_numbers.id = tbl_prescriptions.visit_id
         )"; return DB::statement($prevmedics);
    }
    public static function previousProcedures()
    {
        $prevmedics = "CREATE OR REPLACE VIEW `vw_previous_procedures` AS (
        SELECT
        tbl_items.id AS item_id,
        tbl_items.item_name,
        tbl_patient_procedures.patient_id,
        tbl_item_type_mappeds.item_category,
		tbl_accounts_numbers.date_attended,
		tbl_accounts_numbers.id AS visit_id,
        tbl_patient_procedures.created_at
         FROM 
         tbl_patient_procedures INNER JOIN  tbl_items ON tbl_items.id = tbl_patient_procedures.item_id
         INNER JOIN  tbl_item_type_mappeds ON tbl_items.id = tbl_item_type_mappeds.item_id
         INNER JOIN  tbl_accounts_numbers ON tbl_accounts_numbers.id = tbl_patient_procedures.visit_date_id
         )"; return DB::statement($prevmedics);
    }

    public static function specialClinics()
    {
        $clinics = "CREATE OR REPLACE VIEW `vw_special_clinics` AS(
        SELECT
         t5.name,
         t5.mobile_number,
        t6.prof_name,
        t2.dept_id AS clinic_id,
              	t2.specialist_id,
              	t2.consultation_id,
              	t2.doctor_requesting_id,
              	t2.id AS refferal_id,
              	t2.summary,
              	t2.priority,
              	t2.received,
              	t2.visit_id,
              	t3.facility_id,
              	t4.first_name,
              	t4.middle_name,
              	t4.last_name,
              	t4.gender,
              	t4.medical_record_number,
              	t4.residence_id,
              	t4.dob,
              	t5.name AS doctor						
               FROM tbl_clinic_instructions t2
               INNER JOIN tbl_accounts_numbers t3 ON t3.id=t2.visit_id                
               INNER JOIN tbl_patients t4 ON t4.id=t3.patient_id                
               INNER JOIN users t5 ON t5.id=t2.doctor_requesting_id   
               INNER JOIN tbl_proffesionals t6 ON t5.user_type=t6.id   
       
        )";
        return DB::statement($clinics);
    }

    public static function specialClinicClients()
    {
        $clients = "CREATE OR REPLACE VIEW `vw_special_clinics_clients` AS (
        SELECT
        tbl_patients.first_name,
        tbl_patients.middle_name,
        tbl_patients.last_name,
        tbl_patients.medical_record_number,
        tbl_patients.gender,
        tbl_patients.dob,
        CASE WHEN TIMESTAMPDIFF(YEAR,dob, CURRENT_DATE) <> 0 THEN CONCAT(TIMESTAMPDIFF(YEAR,dob, CURRENT_DATE), ' Years') ELSE CASE WHEN TIMESTAMPDIFF(MONTH,dob, CURRENT_DATE) <> 0 THEN CONCAT(TIMESTAMPDIFF(MONTH, dob, CURRENT_DATE), ' Months') ELSE CONCAT(TIMESTAMPDIFF(DAY, dob, CURRENT_DATE), ' Days') END END
AS age,       
        tbl_accounts_numbers.account_number,
        tbl_accounts_numbers.patient_id,
        tbl_accounts_numbers.id AS account_id,
        tbl_accounts_numbers.date_attended AS visit_date,        
        tbl_bills_categories.main_category_id,
        tbl_bills_categories.bill_id,
        tbl_accounts_numbers.facility_id,
        tbl_clinic_instructions.id AS transfer_id,
        tbl_clinic_instructions.received,
        tbl_clinic_instructions.visit_id,
        tbl_clinic_instructions.dept_id,
        tbl_clinic_instructions.summary
       FROM
        tbl_patients INNER JOIN tbl_accounts_numbers ON tbl_patients.id=tbl_accounts_numbers.patient_id
        INNER JOIN tbl_bills_categories ON tbl_accounts_numbers.id=tbl_bills_categories.account_id
        INNER JOIN tbl_clinic_instructions ON tbl_accounts_numbers.id = tbl_clinic_instructions.visit_id
        )";
        return DB::statement($clients);
    }

    //PHARMACY

   public static function vw_invoice()
    {


        DB::statement(


            "CREATE OR REPLACE VIEW `vw_invoice` AS
    SELECT distinct tbl_invoice_lines.id,tbl_invoice_lines.id as item_refference,tbl_invoice_lines.invoice_id,tbl_invoice_lines.status_id, tbl_invoice_lines.patient_id, tbl_invoice_lines.quantity, tbl_invoice_lines.payment_filter,
	tbl_invoice_lines.discount, tbl_invoice_lines.facility_id, tbl_invoice_lines.discount_by,
	tbl_invoice_lines.created_at,tbl_patients.medical_record_number,tbl_patients.first_name,
	tbl_patients.last_name,tbl_patients.middle_name,tbl_patients.dob,tbl_patients.gender,
	tbl_item_prices.price, tbl_items.item_name, tbl_item_type_mappeds.item_category,tbl_item_type_mappeds.item_id,
	tbl_accounts_numbers.account_number, tbl_payment_statuses.payment_status 
	FROM tbl_invoice_lines INNER JOIN tbl_item_type_mappeds ON tbl_invoice_lines.item_type_id = tbl_item_type_mappeds.id 
	INNER JOIN tbl_item_prices ON tbl_invoice_lines.item_price_id = tbl_item_prices.ID
	INNER JOIN tbl_items ON tbl_item_prices.item_id = tbl_items.id 
	INNER JOIN tbl_payment_statuses ON tbl_invoice_lines.status_id = tbl_payment_statuses.ID 
	INNER JOIN tbl_encounter_invoices ON tbl_invoice_lines.invoice_id = tbl_encounter_invoices.id INNER JOIN tbl_accounts_numbers ON tbl_encounter_invoices.account_number_id = tbl_accounts_numbers.ID 
	INNER JOIN tbl_patients ON tbl_accounts_numbers.patient_id = tbl_patients.id
	INNER JOIN tbl_pay_cat_sub_categories ON tbl_invoice_lines.payment_filter = tbl_pay_cat_sub_categories.id
WHERE (status_id=1 or status_id=4) and pay_cat_id !=3");

    }

    public static function vw_exemptions()
    {


        DB::statement(

            "CREATE OR REPLACE VIEW  `vw_exemptions` AS
    SELECT distinct tbl_exemptions.id,users.facility_id,tbl_exemptions.status_id,tbl_exemptions.exemption_reason,tbl_exemptions.exemption_no,tbl_exemptions.created_at,
	tbl_exemptions.patient_id,tbl_exemptions.reason_for_revoke,tbl_pay_cat_sub_categories.sub_category_name as exemption_name,
	tbl_exemption_statuses.exemption_status,tbl_patients.first_name,tbl_patients.middle_name,tbl_patients.last_name,tbl_patients.gender,
	tbl_patients.dob,tbl_patients.medical_record_number,users.name,users.mobile_number,tbl_attachments.file_path
	 
	FROM (tbl_exemptions LEFT  JOIN tbl_pay_cat_sub_categories ON tbl_exemptions.exemption_type_id = tbl_pay_cat_sub_categories.id 
	INNER JOIN tbl_exemption_statuses ON tbl_exemptions.status_id = tbl_exemption_statuses.id
	INNER JOIN tbl_patients ON tbl_exemptions.patient_id = tbl_patients.id 
	INNER JOIN users ON tbl_exemptions.user_id = users.id)
	LEFT JOIN tbl_attachments ON tbl_attachments.patient_id = tbl_patients.id

        ");
    }

    public static function vw_receivings()
    {


        DB::statement(
            "CREATE OR REPLACE VIEW  `vw_receivings` AS
    SELECT distinct tbl_receiving_items.id, 
	tbl_receiving_items.item_id,
	tbl_receiving_items.received_from_id as vendor_id,
	tbl_item_type_mappeds.item_code,tbl_items.item_name,
	tbl_item_type_mappeds.item_category,users.name,
	tbl_store_lists.store_name,tbl_store_types.store_type_name,tbl_invoices.invoice_number,
	tbl_invoices.id as invoice_id,
	tbl_transaction_types.transaction_type,tbl_vendors.vendor_name,tbl_vendors.vendor_code,
	 tbl_receiving_items.batch_no,
	 tbl_receiving_items.quantity,
	 tbl_receiving_items.issued_quantity,
	 tbl_receiving_items.control,
	 tbl_receiving_items.requested_amount,
	 tbl_receiving_items.price,tbl_receiving_items.attachment_id,
	 tbl_receiving_items.remarks,
	 tbl_receiving_items.received_store_id as store_id,
	 tbl_receiving_items.facility_id,
	 tbl_receiving_items.expiry_date,
	 tbl_receiving_items.updated_at,
	 tbl_receiving_items.created_at,
	 tbl_receiving_items.received_date,
	 (select t.store_name  from tbl_store_lists  as t where t.id= tbl_receiving_items.requesting_store_id) as issued_store_name
	 
	 
	FROM tbl_receiving_items INNER JOIN tbl_items ON tbl_receiving_items.item_id = tbl_items.id 
	  INNER JOIN tbl_item_type_mappeds ON tbl_item_type_mappeds.item_id = tbl_items.id 
    
	
	INNER JOIN tbl_invoices ON tbl_receiving_items.invoice_refference = tbl_invoices.id 
	INNER JOIN tbl_vendors ON tbl_receiving_items.received_from_id = tbl_vendors.id 
	INNER JOIN tbl_transaction_types ON tbl_receiving_items.transaction_type_id = tbl_transaction_types.id
	INNER JOIN users ON tbl_receiving_items.user_id = users.id
    inner join tbl_store_lists on tbl_receiving_items.received_store_id = tbl_store_lists.id
	INNER JOIN tbl_store_types ON tbl_store_lists.store_type_id = tbl_store_types.id

        ");
    }
    public static function vw_pharmacy_items()
    {


        DB::statement(
            "CREATE OR REPLACE VIEW  `vw_pharmacy_items` AS
    SELECT distinct tbl_items.id,tbl_items.id as item_id,tbl_item_type_mappeds.item_code,tbl_items.item_name,
	tbl_item_type_mappeds.item_category,
	tbl_item_type_mappeds.Dose_formulation,
	tbl_item_type_mappeds.sub_item_category,
	tbl_item_type_mappeds.unit_of_measure,
	tbl_item_type_mappeds.dispensing_unit
	 
	FROM tbl_items INNER JOIN tbl_item_type_mappeds ON tbl_item_type_mappeds.item_id = tbl_items.id 
	   where tbl_item_type_mappeds.item_category ='Medication' OR tbl_item_type_mappeds.item_category ='Medical Supplies'
        ");
    }

    public static function vw_substore()
    {


        DB::statement(
            "CREATE OR REPLACE VIEW  `vw_substore` AS
    SELECT distinct tbl_sub_stores.id,tbl_sub_stores.item_id,
	tbl_item_type_mappeds.item_code,tbl_items.item_name,
	tbl_item_type_mappeds.item_category,
	tbl_store_lists.store_name,
	tbl_store_lists.id as sub_store_id,
	tbl_store_types.store_type_name, 
	tbl_transaction_types.transaction_type,
	 tbl_sub_stores.batch_no,
 tbl_sub_stores.control,
	 tbl_sub_stores.updated_at,
	 tbl_sub_stores.created_at,
	 tbl_sub_stores.quantity,
	 tbl_sub_stores.quantity_issued,
	 tbl_sub_stores.request_amount,
	 tbl_sub_stores.received_from_id,
	 tbl_sub_stores.issued_store_id as store_id,
	  (select t.store_name from tbl_store_lists as t where t.id= tbl_sub_stores.issued_store_id ) as sub_store_name,
	  (select t.store_name  from tbl_store_lists  as t where t.id= tbl_sub_stores.requested_store_id) as issued_store_name
	 
	FROM tbl_sub_stores INNER JOIN tbl_items ON tbl_sub_stores.item_id = tbl_items.id 
	  INNER JOIN tbl_item_type_mappeds ON tbl_item_type_mappeds.item_id = tbl_items.id 
     
	 
	INNER JOIN tbl_transaction_types ON tbl_sub_stores.transaction_type_id = tbl_transaction_types.id
	 
    inner join tbl_store_lists on tbl_sub_stores.issued_store_id = tbl_store_lists.id
	INNER JOIN tbl_store_types ON tbl_store_lists.store_type_id = tbl_store_types.id

       ");
    }

    public static function vw_dispensing_window()
    {

        DB::statement(
            "CREATE OR REPLACE VIEW  `vw_dispensing_window` AS
    SELECT distinct tbl_dispensers.id, 
	tbl_dispensers.item_id, 
	tbl_item_type_mappeds.item_code,tbl_items.item_name,
	tbl_item_type_mappeds.item_category,
	tbl_store_lists.store_name,tbl_store_types.store_type_name, 
	tbl_transaction_types.transaction_type,
	 tbl_dispensers.batch_no,
tbl_dispensers.control,
	 tbl_dispensers.created_at,
	 tbl_dispensers.quantity_received,
	 tbl_dispensers.quantity_dispensed,
	 
	 
	 tbl_dispensers.received_from_id,
	 tbl_dispensers.dispenser_id as store_id
	  
	  
	  
	FROM tbl_dispensers INNER JOIN tbl_items ON tbl_dispensers.item_id = tbl_items.id 
	  INNER JOIN tbl_item_type_mappeds ON tbl_item_type_mappeds.item_id = tbl_items.id 
     
	  
	INNER JOIN tbl_transaction_types ON tbl_dispensers.transaction_type_dispensed_id = tbl_transaction_types.id
	 
    inner join tbl_store_lists on tbl_dispensers.dispenser_id = tbl_store_lists.id
	INNER JOIN tbl_store_types ON tbl_store_lists.store_type_id = tbl_store_types.id

        ");
    }

    public static function vw_sub_store_incoming_order()
    {


        DB::statement(

            "CREATE OR REPLACE VIEW  `vw_sub_store_incoming_order` AS
    SELECT distinct tbl_sub_stores.id, 
	tbl_sub_stores.item_id,
	tbl_sub_stores.issued_store_id as store_id,
	tbl_sub_stores.created_at,
	tbl_sub_stores.order_no,
	 tbl_store_request_statuses.store_request_status,
	tbl_sub_stores.requested_store_id ,
	tbl_sub_stores.request_amount,
	tbl_item_type_mappeds.item_code,tbl_items.item_name,
	tbl_item_type_mappeds.item_category,tbl_store_lists.store_type_id,
	tbl_store_lists.store_name,tbl_store_types.store_type_name,
	tbl_store_types.id as requested_store_type_id,
	  (select t.store_name from tbl_store_lists as t where t.id= tbl_sub_stores.requested_store_id ) as requesting_store_name,
	  (select t1.store_type_id from tbl_store_lists as t1 where t1.id= tbl_sub_stores.requested_store_id ) as requesting_store_type_id
	
	FROM tbl_sub_stores INNER JOIN tbl_items ON tbl_sub_stores.item_id = tbl_items.id 
	  INNER JOIN tbl_item_type_mappeds ON tbl_item_type_mappeds.item_id = tbl_items.id 
	   
	inner join tbl_store_lists on tbl_sub_stores.issued_store_id = tbl_store_lists.id
	INNER JOIN tbl_store_types ON tbl_store_lists.store_type_id = tbl_store_types.id
	INNER JOIN tbl_store_request_statuses ON tbl_sub_stores.request_status_id = tbl_store_request_statuses.id
        and tbl_sub_stores.requested_store_id is not null and tbl_sub_stores.request_status_id != 1
        ");

    }

    public static function vw_main_store_incoming_order()
    {


        DB::statement(


            "CREATE OR REPLACE VIEW  `vw_main_store_incoming_order` AS
        SELECT distinct tbl_receiving_items.id,
        tbl_receiving_items.item_id,
        tbl_receiving_items.received_store_id as store_id,
        tbl_receiving_items.created_at,
        tbl_receiving_items.order_no,
         tbl_store_request_statuses.store_request_status,

        tbl_receiving_items.requested_amount,
        tbl_receiving_items.requesting_store_id,
        tbl_store_types.id as requested_store_type_id,
        tbl_item_type_mappeds.item_code,tbl_items.item_name,
        tbl_item_type_mappeds.item_category,
        tbl_store_lists.store_name,tbl_store_types.store_type_name,

    (select t.store_name  from tbl_store_lists  as t where t.id= tbl_receiving_items.requesting_store_id ) as requesting_store_name,
         (select t.store_type_id  from tbl_store_lists  as t where t.id= tbl_receiving_items.requesting_store_id ) as requesting_store_type_id

        FROM tbl_receiving_items INNER JOIN tbl_items ON tbl_receiving_items.item_id = tbl_items.id
          INNER JOIN tbl_item_type_mappeds ON tbl_item_type_mappeds.item_id = tbl_items.id

        inner join tbl_store_lists on tbl_receiving_items.received_store_id = tbl_store_lists.id
        INNER JOIN tbl_store_types ON tbl_store_lists.store_type_id = tbl_store_types.id
        INNER JOIN tbl_store_request_statuses ON tbl_receiving_items.request_status_id = tbl_store_request_statuses.id
            and tbl_receiving_items.requesting_store_id is not null and tbl_receiving_items.request_status_id != 1
            ");
    }

    public static function vw_receivings_issuing_details()
    {


        DB::statement(

            "CREATE OR REPLACE VIEW  `vw_receivings_issuing_details` AS
        SELECT distinct tbl_receiving_items.id,

        tbl_item_type_mappeds.item_code,tbl_items.item_name,
        tbl_item_type_mappeds.item_category,users.name,
        tbl_store_lists.store_name,tbl_store_types.store_type_name,tbl_invoices.invoice_number,

        tbl_transaction_types.transaction_type,tbl_vendors.vendor_name,tbl_vendors.vendor_code,
         tbl_receiving_items.batch_no,
         tbl_receiving_items.quantity,
         tbl_receiving_items.issued_quantity,
         tbl_receiving_items.requested_amount,
         tbl_receiving_items.price,tbl_receiving_items.attachment_id,
         tbl_receiving_items.remarks,
         tbl_receiving_items.facility_id,
         tbl_receiving_items.expiry_date,
         tbl_receiving_items.created_at,
         tbl_receiving_items.received_date,
         (select t.store_name  from tbl_store_lists  as t where t.id= tbl_receiving_items.requesting_store_id) as issued_store_name

        FROM tbl_receiving_items INNER JOIN tbl_items ON tbl_receiving_items.item_id = tbl_items.id
          INNER JOIN tbl_item_type_mappeds ON tbl_item_type_mappeds.item_id = tbl_items.id


        INNER JOIN tbl_invoices ON tbl_receiving_items.invoice_refference = tbl_invoices.id
        INNER JOIN tbl_vendors ON tbl_receiving_items.received_from_id = tbl_vendors.id
        INNER JOIN tbl_transaction_types ON tbl_receiving_items.transaction_type_id = tbl_transaction_types.id
        INNER JOIN users ON tbl_receiving_items.user_id = users.id
        inner join tbl_store_lists on tbl_receiving_items.received_store_id = tbl_store_lists.id
        INNER JOIN tbl_store_types ON tbl_store_lists.store_type_id = tbl_store_types.id

            ");
    }



    public static function vw_main_store_balance()
    {


        DB::statement(

            "CREATE OR REPLACE VIEW  `vw_main_store_balance` AS
        SELECT distinct vw_main_store_summary_balance.item_id, vw_main_store_summary_balance.item_name,
        vw_main_store_summary_balance.item_category,
         vw_main_store_summary_balance.batch_no,
        vw_main_store_summary_balance.store_id,
        vw_main_store_summary_balance.store_name,
        sum(balance) as balance

        FROM vw_main_store_summary_balance
           GROUP BY vw_main_store_summary_balance.item_id,vw_main_store_summary_balance.store_name
            ");
    }


//    public static function vw_dispensing_balance()
//    {
//
//
//        DB::statement(
//
//            "CREATE OR REPLACE VIEW  `vw_dispensing_balance` AS
//        SELECT distinct tbl_dispensers.id,
//        tbl_dispensers.item_id,
//
//        tbl_item_type_mappeds.item_code,tbl_items.item_name,
//        tbl_item_type_mappeds.item_category,
//        tbl_store_lists.store_name,tbl_store_types.store_type_name,
//         tbl_dispensers.batch_no,
//
//         min(quantity_received) as balance,
//
//         tbl_dispensers.dispenser_id as store_id
//
//        FROM tbl_dispensers INNER JOIN tbl_items ON tbl_dispensers.item_id = tbl_items.id
//          INNER JOIN tbl_item_type_mappeds ON tbl_item_type_mappeds.item_id = tbl_items.id
//
//
//        INNER JOIN tbl_transaction_types ON tbl_dispensers.transaction_type_dispensed_id = tbl_transaction_types.id
//
//        inner join tbl_store_lists on tbl_dispensers.dispenser_id = tbl_store_lists.id
//        INNER JOIN tbl_store_types ON tbl_store_lists.store_type_id = tbl_store_types.id
//           group by tbl_dispensers.dispenser_id,tbl_dispensers.batch_no
//           ");
//
//    }


    public static function vw_substore_balance()
    {


        DB::statement(

            "CREATE OR REPLACE VIEW  `vw_substore_balance` AS
        SELECT distinct tbl_sub_stores.id,tbl_sub_stores.item_id,
        tbl_item_type_mappeds.item_code,tbl_items.item_name,
        tbl_item_type_mappeds.item_category,
        tbl_store_lists.store_name,
         tbl_sub_stores.batch_no,

         min(quantity) as balance


        FROM tbl_sub_stores INNER JOIN tbl_items ON tbl_sub_stores.item_id = tbl_items.id
          INNER JOIN tbl_item_type_mappeds ON tbl_item_type_mappeds.item_id = tbl_items.id


        INNER JOIN tbl_transaction_types ON tbl_sub_stores.transaction_type_id = tbl_transaction_types.id

        inner join tbl_store_lists on tbl_sub_stores.issued_store_id = tbl_store_lists.id
        INNER JOIN tbl_store_types ON tbl_store_lists.store_type_id = tbl_store_types.id
           group by tbl_sub_stores.issued_store_id,tbl_sub_stores.batch_no
           ");
    }


    public static function vw_gbv_vacs()
    {


        DB::statement(
            "CREATE OR REPLACE VIEW  `vw_gbv_vacs` AS
        SELECT distinct tbl_gbv_vacs.id, tbl_gbv_vacs.patient_id,tbl_gbv_vacs.followup_date,
         tbl_gbv_vacs.date_of_event,tbl_gbv_vacs.description,
        tbl_gbv_vacs.service,tbl_gbv_vacs.other_description,tbl_gbv_vacs.referral_reason,
        tbl_patients.medical_record_number,
        tbl_patients.gender,tbl_patients.dob,tbl_patients.first_name,
        tbl_patients.middle_name,tbl_patients.last_name,tbl_gbv_vacs.facility_id,
        tbl_referral_institutions.institution_type,tbl_violence_categories.violence_type_category,
        tbl_violence_types.violence_type_name,tbl_attachments.file_path


        FROM (tbl_gbv_vacs INNER JOIN tbl_patients ON tbl_patients.id = tbl_gbv_vacs.patient_id
          INNER JOIN tbl_referral_institutions ON tbl_referral_institutions.id = tbl_gbv_vacs.referral_id
          INNER JOIN tbl_violence_categories ON tbl_violence_categories.id = tbl_gbv_vacs.violence_category_id
          INNER JOIN tbl_violence_types ON tbl_violence_types.id = tbl_gbv_vacs.violence_type_id )
        LEFT JOIN tbl_attachments ON tbl_attachments.patient_id = tbl_gbv_vacs.patient_id


           ");
    }

    public static function vw_dispensing_item_balance()
    {


        DB::statement(
            "CREATE OR REPLACE VIEW  `vw_dispensing_item_balance` AS
        SELECT distinct
        tbl_dispensers.item_id,


        tbl_payments_categories.category_description,
        tbl_payments_categories.id as main_category_id,

         tbl_store_lists.facility_id,

          sum(quantity_received) as balance
        FROM tbl_dispensers INNER JOIN tbl_items ON tbl_dispensers.item_id = tbl_items.id
        INNER JOIN tbl_store_lists ON tbl_dispensers.dispenser_id = tbl_store_lists.id
        INNER JOIN tbl_item_prices ON tbl_dispensers.item_id = tbl_item_prices.item_id
        INNER JOIN tbl_pay_cat_sub_categories ON tbl_pay_cat_sub_categories.id = tbl_item_prices.sub_category_id
        INNER JOIN tbl_payments_categories ON tbl_payments_categories.id = tbl_pay_cat_sub_categories.pay_cat_id
           where control='l'
           group by item_id, tbl_payments_categories.id, tbl_payments_categories.category_description,  tbl_store_lists.facility_id,tbl_payments_categories.id

            ");
    }


    public static function vw_shop_item_balance()
    {


        DB::statement(
            "CREATE OR REPLACE VIEW  `vw_shop_item_balance` AS
        SELECT distinct
        tbl_dispensers.item_id,


        tbl_payments_categories.category_description,
        tbl_payments_categories.id as main_category_id,
        tbl_pay_cat_sub_categories.id as pay_cat_id,

         tbl_store_lists.facility_id,

          sum(quantity_received) as balance
        FROM tbl_dispensers INNER JOIN tbl_items ON tbl_dispensers.item_id = tbl_items.id
        INNER JOIN tbl_store_lists ON tbl_dispensers.dispenser_id = tbl_store_lists.id
        INNER JOIN tbl_item_prices ON tbl_dispensers.item_id = tbl_item_prices.item_id
        INNER JOIN tbl_pay_cat_sub_categories ON tbl_pay_cat_sub_categories.id = tbl_item_prices.sub_category_id
        INNER JOIN tbl_payments_categories ON tbl_payments_categories.id = tbl_pay_cat_sub_categories.pay_cat_id
           where control='l' and tbl_pay_cat_sub_categories.id=12
            
            ");
    }

    public static function vw_prescriptions_dispensed()
    {


        DB::statement(


            "CREATE OR REPLACE  VIEW 	`vw_prescriptions_dispensed` AS

        SELECT distinct tbl_prescriptions.id,
        tbl_prescriptions.updated_at as date,tbl_patients.id as patient_id,tbl_patients.medical_record_number,tbl_patients.first_name,
        tbl_patients.last_name,tbl_patients.middle_name,tbl_patients.dob,tbl_patients.gender,
          tbl_items.item_name, tbl_item_type_mappeds.item_category,tbl_prescriptions.item_id,
         tbl_item_type_mappeds.item_code,
    tbl_prescriptions.frequency,tbl_prescriptions.quantity,tbl_prescriptions.duration,tbl_prescriptions.dose,tbl_prescriptions.start_date,
        tbl_prescriptions.instruction,tbl_prescriptions.dispensing_status,
         users.name as dispensed_by,tbl_prescriptions.dispenser_id,users.facility_id
        from    tbl_prescriptions inner join tbl_patients on tbl_prescriptions.patient_id = tbl_patients.id
        inner join tbl_items on tbl_prescriptions.item_id = tbl_items.id
        inner join tbl_item_type_mappeds on tbl_items.id = tbl_item_type_mappeds.item_id
        inner join tbl_invoice_lines on tbl_item_type_mappeds.id = tbl_invoice_lines.item_type_id
        INNER JOIN users ON tbl_prescriptions.prescriber_id = users.id

    where tbl_prescriptions.dispensing_status=1


           ");
    }


    public static function vw_prescriptions()
    {


        DB::statement(
            " 
    CREATE or REPLACE VIEW 	`vw_prescriptions` AS
        SELECT distinct tbl_prescriptions.id,tbl_prescriptions.out_of_stock,
        tbl_invoice_lines.created_at,tbl_patients.id as patient_id,tbl_patients.medical_record_number,tbl_patients.first_name,
        tbl_patients.last_name,tbl_patients.middle_name,tbl_patients.dob,tbl_patients.gender,
          tbl_items.item_name, tbl_item_type_mappeds.item_category,tbl_prescriptions.item_id,
        tbl_invoice_lines.status_id as payment_status_id,tbl_invoice_lines.payment_filter,tbl_pay_cat_sub_categories.pay_cat_id,
    tbl_prescriptions.frequency,tbl_prescriptions.quantity,tbl_prescriptions.duration,tbl_prescriptions.dose,tbl_prescriptions.start_date,
        tbl_prescriptions.instruction,tbl_prescriptions.dispensing_status,
         users.name,users.facility_id

        from    tbl_prescriptions inner join tbl_patients on tbl_prescriptions.patient_id = tbl_patients.id
        inner join tbl_items on tbl_prescriptions.item_id = tbl_items.id
        inner join tbl_item_type_mappeds on tbl_items.id = tbl_item_type_mappeds.item_id
        inner join tbl_invoice_lines on tbl_item_type_mappeds.id = tbl_invoice_lines.item_type_id
        inner join tbl_pay_cat_sub_categories on tbl_pay_cat_sub_categories.id = tbl_invoice_lines.payment_filter
        INNER JOIN users ON tbl_prescriptions.prescriber_id = users.id where tbl_invoice_lines.status_id=2 or tbl_pay_cat_sub_categories.pay_cat_id>1


            ");


    }



    public static function vw_vulnerables()
    {


        DB::statement(

            "CREATE OR REPLACE VIEW `vw_vulnerables` AS
        SELECT YEAR(t1.date_of_event) as year,t1.facility_id,(SELECT COUNT(gender) FROM `tbl_gbv_vacs` INNER JOIN `tbl_patients`
        ON `tbl_gbv_vacs`.patient_id = 	`tbl_patients`.id AND `tbl_patients`.gender = 'Female'
            AND TIMESTAMPDIFF(MONTH, `tbl_patients`.dob, CURRENT_DATE) <= 59  WHERE  YEAR(`tbl_gbv_vacs`.date_of_event) = YEAR(t1.date_of_event)) AS female_under_59,
    (SELECT COUNT(gender) FROM `tbl_gbv_vacs` INNER JOIN `tbl_patients`
        ON `tbl_gbv_vacs`.patient_id = 	`tbl_patients`.id AND `tbl_patients`.gender = 'Male'
            AND TIMESTAMPDIFF(MONTH, `tbl_patients`.dob, CURRENT_DATE) <= 59 WHERE  YEAR(`tbl_gbv_vacs`.date_of_event) = YEAR(t1.date_of_event)) AS male_under_59,
    (SELECT COUNT(gender) FROM `tbl_gbv_vacs` INNER JOIN `tbl_patients`
        ON `tbl_gbv_vacs`.patient_id = 	`tbl_patients`.id
            AND TIMESTAMPDIFF(MONTH, `tbl_patients`.dob, CURRENT_DATE) <= 59 WHERE YEAR(`tbl_gbv_vacs`.date_of_event) = YEAR(t1.date_of_event)) AS total_under_59,

        (SELECT COUNT(gender) FROM `tbl_gbv_vacs` INNER JOIN `tbl_patients`
        ON `tbl_gbv_vacs`.patient_id = 	`tbl_patients`.id AND `tbl_patients`.gender = 'Female'
            AND TIMESTAMPDIFF(MONTH, `tbl_patients`.dob, CURRENT_DATE) BETWEEN 60 AND 108 WHERE YEAR(`tbl_gbv_vacs`.date_of_event) = YEAR(t1.date_of_event)) AS female_under_9,
    (SELECT COUNT(gender) FROM `tbl_gbv_vacs` INNER JOIN `tbl_patients`
        ON `tbl_gbv_vacs`.patient_id = 	`tbl_patients`.id AND `tbl_patients`.gender = 'Male'
            AND TIMESTAMPDIFF(MONTH, `tbl_patients`.dob, CURRENT_DATE)  BETWEEN 60 AND 108 WHERE YEAR(`tbl_gbv_vacs`.date_of_event) = YEAR(t1.date_of_event)) AS male_under_9,
    (SELECT COUNT(gender) FROM `tbl_gbv_vacs` INNER JOIN `tbl_patients`
        ON `tbl_gbv_vacs`.patient_id = 	`tbl_patients`.id
            AND TIMESTAMPDIFF(MONTH, `tbl_patients`.dob, CURRENT_DATE)  BETWEEN 60 AND 108 WHERE YEAR(`tbl_gbv_vacs`.date_of_event) = YEAR(t1.date_of_event)) AS total_under_9,

        (SELECT COUNT(gender) FROM `tbl_gbv_vacs` INNER JOIN `tbl_patients`
        ON `tbl_gbv_vacs`.patient_id = 	`tbl_patients`.id AND `tbl_patients`.gender = 'Female'
            AND TIMESTAMPDIFF(MONTH, `tbl_patients`.dob, CURRENT_DATE) BETWEEN 120 AND 288 WHERE YEAR(`tbl_gbv_vacs`.date_of_event) = YEAR(t1.date_of_event)) AS female_under_24,
    (SELECT COUNT(gender) FROM `tbl_gbv_vacs` INNER JOIN `tbl_patients`
        ON `tbl_gbv_vacs`.patient_id = 	`tbl_patients`.id AND `tbl_patients`.gender = 'Male'
            AND TIMESTAMPDIFF(MONTH, `tbl_patients`.dob, CURRENT_DATE)  BETWEEN 120 AND 288 WHERE YEAR(`tbl_gbv_vacs`.date_of_event) = YEAR(t1.date_of_event)) AS male_under_24,
    (SELECT COUNT(gender) FROM `tbl_gbv_vacs` INNER JOIN `tbl_patients`
        ON `tbl_gbv_vacs`.patient_id = 	`tbl_patients`.id
            AND TIMESTAMPDIFF(MONTH, `tbl_patients`.dob, CURRENT_DATE)  BETWEEN 120 AND 288 WHERE YEAR(`tbl_gbv_vacs`.date_of_event) = YEAR(t1.date_of_event)) AS total_under_24,

        (SELECT COUNT(gender) FROM `tbl_gbv_vacs` INNER JOIN `tbl_patients`
        ON `tbl_gbv_vacs`.patient_id = 	`tbl_patients`.id AND `tbl_patients`.gender = 'Female'
            AND TIMESTAMPDIFF(MONTH, `tbl_patients`.dob, CURRENT_DATE)  >=300 WHERE YEAR(`tbl_gbv_vacs`.date_of_event) = YEAR(t1.date_of_event)) AS female_above_25,
    (SELECT COUNT(gender) FROM `tbl_gbv_vacs` INNER JOIN `tbl_patients`
        ON `tbl_gbv_vacs`.patient_id = 	`tbl_patients`.id AND `tbl_patients`.gender = 'Male'
            AND TIMESTAMPDIFF(MONTH, `tbl_patients`.dob, CURRENT_DATE)   >=300 WHERE YEAR(`tbl_gbv_vacs`.date_of_event) = YEAR(t1.date_of_event)) AS male_above_25,
    (SELECT COUNT(gender) FROM `tbl_gbv_vacs` INNER JOIN `tbl_patients`
        ON `tbl_gbv_vacs`.patient_id = 	`tbl_patients`.id
            AND TIMESTAMPDIFF(MONTH, `tbl_patients`.dob, CURRENT_DATE)  >=300 WHERE YEAR(`tbl_gbv_vacs`.date_of_event) = YEAR(t1.date_of_event)) AS total_above_25,

        (SELECT COUNT(gender) FROM `tbl_gbv_vacs` INNER JOIN `tbl_patients`
        ON `tbl_gbv_vacs`.patient_id = 	`tbl_patients`.id AND `tbl_patients`.gender = 'Female' WHERE YEAR(`tbl_gbv_vacs`.date_of_event) = YEAR(t1.date_of_event)) AS total_female,
    (SELECT COUNT(gender) FROM `tbl_gbv_vacs` INNER JOIN `tbl_patients`
        ON `tbl_gbv_vacs`.patient_id = 	`tbl_patients`.id AND `tbl_patients`.gender = 'Male' WHERE YEAR(`tbl_gbv_vacs`.date_of_event) = YEAR(t1.date_of_event)) AS total_male,
    (SELECT COUNT(gender) FROM `tbl_gbv_vacs` INNER JOIN `tbl_patients`
        ON `tbl_gbv_vacs`.patient_id = 	`tbl_patients`.id WHERE YEAR(`tbl_gbv_vacs`.date_of_event) = YEAR(t1.date_of_event)) AS total

        FROM `tbl_gbv_vacs` AS t1 GROUP BY year,date_of_event,facility_id
        ");

    }

    public static function vw_temporary_exemptions()
    {


        DB::statement(

            "CREATE OR REPLACE VIEW  `vw_temporary_exemptions` AS
        SELECT distinct  users.facility_id,tbl_exemptions.status_id,tbl_exemptions.exemption_reason,tbl_exemptions.exemption_no,tbl_invoice_lines.created_at,
        tbl_exemptions.patient_id,tbl_exemptions.reason_for_revoke,tbl_invoice_lines.payment_filter,
         tbl_patients.first_name,tbl_patients.middle_name,tbl_patients.last_name,tbl_patients.gender,
        tbl_patients.dob,tbl_patients.medical_record_number,users.name,users.mobile_number

        FROM tbl_invoice_lines inner  JOIN tbl_exemptions ON tbl_invoice_lines.patient_id = tbl_exemptions.patient_id

        INNER JOIN tbl_patients ON tbl_exemptions.patient_id = tbl_patients.id
        INNER JOIN users ON tbl_exemptions.user_id = users.id

        where payment_filter is not NULL and tbl_invoice_lines.status_id=1

           ");

    }

public static function FolioItems()
        {
            $diag ="CREATE OR REPLACE VIEW `vw_folio_items` AS (SELECT
            t1.item_price_id,
            t1.quantity AS ItemQuantity,
            t6.name AS createdBy,
            t7.prof_name AS proffesion,
            t2.item_code,
            t3.price AS UnitPrice,
            t3.facility_id,
            (t1.quantity * t3.price) AS AmountClaimed,
            t4.account_number_id AS visit_id ,             
            t5.date_attended             
            FROM tbl_invoice_lines t1            
            INNER JOIN  tbl_item_type_mappeds t2 ON t2.id = t1.item_type_id
            INNER JOIN  tbl_item_prices t3 ON t3.id = t1.item_price_id
            INNER JOIN  tbl_encounter_invoices t4 ON t4.id = t1.invoice_id
            INNER JOIN  tbl_accounts_numbers t5 ON t5.id = t4.account_number_id
            INNER JOIN  users t6 ON t6.id = t1.user_id
            INNER JOIN  tbl_proffesionals t7 ON t7.id = t6.user_type
            WHERE 	t1.payment_filter=4
              )";
            return DB::statement($diag);
        }


    public static function nhifPatients()
    {
        $nhif = "CREATE OR REPLACE VIEW `vw_nhif_patients` AS(SELECT 
        tbl_patients.id AS patient_id,
        tbl_accounts_numbers.date_attended,
        tbl_accounts_numbers.account_number,
        tbl_accounts_numbers.account_number AS FolioNo,
        tbl_accounts_numbers.id AS visit_id,
        tbl_accounts_numbers.card_no,
        tbl_accounts_numbers.membership_number,
        tbl_accounts_numbers.authorization_number,
        tbl_accounts_numbers.facility_id,
        (SELECT residence_name FROM tbl_residences t1 WHERE t1.id=tbl_patients.residence_id GROUP BY tbl_patients.residence_id) AS residence_name,
		
	   (SELECT occupation_name FROM tbl_occupations t1 WHERE t1.id=tbl_patients.occupation_id GROUP BY tbl_patients.occupation_id) AS occupation_name,
        tbl_accounts_numbers.created_at,
		MONTH(tbl_accounts_numbers.created_at) AS ClaimMonth,
		YEAR(tbl_accounts_numbers.created_at) AS ClaimYear,
        tbl_patients.first_name,
        tbl_patients.middle_name,
        tbl_patients.last_name,
        tbl_patients.gender,
		(SELECT t1.facility_code FROM tbl_facilities t1 WHERE t1.id=tbl_patients.facility_id GROUP BY t1.id) AS facility_code,
        tbl_patients.dob,
        CASE WHEN TIMESTAMPDIFF(YEAR,dob, CURRENT_DATE) <> 0 THEN CONCAT(TIMESTAMPDIFF(YEAR,dob, CURRENT_DATE), ' Years') ELSE CASE WHEN TIMESTAMPDIFF(MONTH,dob, CURRENT_DATE) <> 0 THEN CONCAT(TIMESTAMPDIFF(MONTH, dob, CURRENT_DATE), ' Months') ELSE CONCAT(TIMESTAMPDIFF(DAY, dob, CURRENT_DATE), ' Days') END END
AS age,
        tbl_patients.medical_record_number,        
        tbl_bills_categories.main_category_id,
        tbl_bills_categories.bill_id,
        tbl_patients.mobile_number
        FROM tbl_bills_categories INNER JOIN tbl_patients ON tbl_patients.id=tbl_bills_categories.patient_id
        INNER JOIN tbl_accounts_numbers ON tbl_accounts_numbers.id = tbl_bills_categories.account_id
        WHERE tbl_bills_categories.main_category_id =2 AND bill_id =4
        )";
        return DB::statement($nhif);

    }


    public static function bimaInvestigations()
    {
        $bima = "CREATE OR REPLACE VIEW `vw_bima_investigations` AS(SELECT
         tbl_accounts_numbers.date_attended,
         tbl_accounts_numbers.facility_id,
         tbl_accounts_numbers.patient_id,
         tbl_items.item_name,
         tbl_item_type_mappeds.item_code AS inv_code,
         tbl_orders.id,
         tbl_item_prices.price,
         users.name AS user_name,
         users.mobile_number,
         tbl_proffesionals.prof_name
         FROM tbl_requests 
         INNER JOIN tbl_accounts_numbers ON tbl_requests.visit_date_id=tbl_accounts_numbers.id
         INNER JOIN tbl_orders ON tbl_requests.id=tbl_orders.order_id
         INNER JOIN tbl_item_prices ON tbl_item_prices.item_id=tbl_orders.test_id
         INNER JOIN tbl_items ON tbl_items.id=tbl_orders.test_id
         INNER JOIN tbl_item_type_mappeds ON tbl_item_prices.item_id=tbl_item_type_mappeds.item_id
         INNER JOIN users ON users.id= tbl_requests.doctor_id
         INNER JOIN tbl_proffesionals ON tbl_proffesionals.id= users.user_type
         )";
        return DB::statement($bima);
    }
    public static function bimaProcedures()
    {
        $bima = "CREATE OR REPLACE VIEW `vw_bimaProcedures` AS(SELECT
         tbl_accounts_numbers.date_attended,
         tbl_accounts_numbers.facility_id,
         tbl_accounts_numbers.patient_id,
         tbl_items.item_name,
         tbl_item_type_mappeds.item_code AS proc_code,
         tbl_item_type_mappeds.item_category,
         tbl_item_prices.price,
        tbl_patient_procedures.created_at
         FROM tbl_requests 
         INNER JOIN tbl_accounts_numbers ON tbl_requests.visit_date_id=tbl_accounts_numbers.id
         INNER JOIN tbl_patient_procedures ON tbl_patient_procedures.visit_date_id=tbl_accounts_numbers.id
         INNER JOIN tbl_item_prices ON tbl_item_prices.item_id=tbl_patient_procedures.item_id
         INNER JOIN tbl_items ON tbl_items.id=tbl_patient_procedures.item_id
         INNER JOIN tbl_item_type_mappeds ON tbl_item_prices.item_id=tbl_item_type_mappeds.item_id
         )";
        return DB::statement($bima);
    }


    public static function bimaPrescriptions()
    {
        $bima = "CREATE OR REPLACE VIEW `vw_bima_prescriptions` AS(SELECT
         tbl_prescriptions.start_date,
         tbl_prescriptions.patient_id,
         tbl_items.id,
		 t4.bill_id AS insuarance_id,
         tbl_items.item_name AS medicine,
         tbl_item_prices.facility_id,         
         tbl_item_type_mappeds.item_code AS medi_code,
         tbl_prescriptions.quantity,
         tbl_item_prices.price AS medicine_price
         FROM tbl_prescriptions 
         INNER JOIN tbl_items ON tbl_prescriptions.item_id=tbl_items.id
         INNER JOIN tbl_item_prices ON tbl_items.id=tbl_item_prices.item_id
         INNER JOIN tbl_item_type_mappeds ON tbl_items.id=tbl_item_type_mappeds.item_id
         INNER JOIN tbl_bills_categories t4 ON t4.account_id=tbl_prescriptions.visit_id
		 WHERE 	t4.main_category_id=2
         )";
        return DB::statement($bima);

    } 

    public static function createViewOutOfstock(){
        $drugs_os="CREATE or REPLACE VIEW 	`vw_os_drugs` AS
        (SELECT distinct tbl_prescriptions.id,tbl_prescriptions.out_of_stock,
         
          tbl_items.item_name,DATE(tbl_prescriptions.created_at) as date_out_of_stock,TIME(tbl_prescriptions.created_at) AS time_os,tbl_accounts_numbers.facility_id

        from    tbl_items inner join tbl_prescriptions on tbl_prescriptions.item_id = tbl_items.id
      inner join tbl_accounts_numbers on tbl_prescriptions.patient_id=tbl_accounts_numbers.patient_id
         where tbl_prescriptions.out_of_stock='OS'
            )";
        return DB::statement($drugs_os);

    }

    public static function createViewTestsOutOfstock(){
        $drugs_os="CREATE or REPLACE VIEW 	`vw_unavailable_tests` AS
        (SELECT DISTINCT t1.id,t1.item_name,DATE(t2.created_at) as date_out_of_stock,TIME(t2.created_at) AS time_os,t3.facility_id

        from    tbl_items t1 
        inner join tbl_unavailable_tests t2 on t2.item_id = t1.id
        inner join tbl_patients t4 on t4.id=t2.patient_id 
       
      inner join tbl_accounts_numbers t3 on t2.patient_id=t3.patient_id 
       
         
           )";
        return DB::statement($drugs_os);

    }




    public static function vw_violance()
    {


        DB::statement(

            "CREATE OR REPLACE VIEW `vw_violance` AS
        SELECT YEAR(t1.date_of_event) as year,violence_type_category,t1.facility_id,(SELECT COUNT(gender) FROM `tbl_gbv_vacs` INNER JOIN `tbl_patients`
        ON `tbl_gbv_vacs`.patient_id = 	`tbl_patients`.id AND `tbl_patients`.gender = 'Female'
            AND TIMESTAMPDIFF(MONTH, `tbl_patients`.dob, CURRENT_DATE) <= 59  WHERE  YEAR(`tbl_gbv_vacs`.date_of_event) = YEAR(t1.date_of_event) AND `tbl_gbv_vacs`.violence_category_id = t2.id) AS female_under_59,
    (SELECT COUNT(gender) FROM `tbl_gbv_vacs` INNER JOIN `tbl_patients`
        ON `tbl_gbv_vacs`.patient_id = 	`tbl_patients`.id AND `tbl_patients`.gender = 'Male'
            AND TIMESTAMPDIFF(MONTH, `tbl_patients`.dob, CURRENT_DATE) <= 59 WHERE  YEAR(`tbl_gbv_vacs`.date_of_event) = YEAR(t1.date_of_event) AND `tbl_gbv_vacs`.violence_category_id = t2.id) AS male_under_59,
    (SELECT COUNT(gender) FROM `tbl_gbv_vacs` INNER JOIN `tbl_patients`
        ON `tbl_gbv_vacs`.patient_id = 	`tbl_patients`.id
            AND TIMESTAMPDIFF(MONTH, `tbl_patients`.dob, CURRENT_DATE) <= 59 WHERE YEAR(`tbl_gbv_vacs`.date_of_event) = YEAR(t1.date_of_event) AND `tbl_gbv_vacs`.violence_category_id = t2.id) AS total_under_59,

        (SELECT COUNT(gender) FROM `tbl_gbv_vacs` INNER JOIN `tbl_patients`
        ON `tbl_gbv_vacs`.patient_id = 	`tbl_patients`.id AND `tbl_patients`.gender = 'Female'
            AND TIMESTAMPDIFF(MONTH, `tbl_patients`.dob, CURRENT_DATE) BETWEEN 60 AND 108 WHERE YEAR(`tbl_gbv_vacs`.date_of_event) = YEAR(t1.date_of_event) AND `tbl_gbv_vacs`.violence_category_id = t2.id) AS female_under_9,
    (SELECT COUNT(gender) FROM `tbl_gbv_vacs` INNER JOIN `tbl_patients`
        ON `tbl_gbv_vacs`.patient_id = 	`tbl_patients`.id AND `tbl_patients`.gender = 'Male'
            AND TIMESTAMPDIFF(MONTH, `tbl_patients`.dob, CURRENT_DATE)  BETWEEN 60 AND 108 WHERE YEAR(`tbl_gbv_vacs`.date_of_event) = YEAR(t1.date_of_event) AND `tbl_gbv_vacs`.violence_category_id = t2.id) AS male_under_9,
    (SELECT COUNT(gender) FROM `tbl_gbv_vacs` INNER JOIN `tbl_patients`
        ON `tbl_gbv_vacs`.patient_id = 	`tbl_patients`.id
            AND TIMESTAMPDIFF(MONTH, `tbl_patients`.dob, CURRENT_DATE)  BETWEEN 60 AND 108 WHERE YEAR(`tbl_gbv_vacs`.date_of_event) = YEAR(t1.date_of_event) AND `tbl_gbv_vacs`.violence_category_id = t2.id) AS total_under_9,

        (SELECT COUNT(gender) FROM `tbl_gbv_vacs` INNER JOIN `tbl_patients`
        ON `tbl_gbv_vacs`.patient_id = 	`tbl_patients`.id AND `tbl_patients`.gender = 'Female'
            AND TIMESTAMPDIFF(MONTH, `tbl_patients`.dob, CURRENT_DATE) BETWEEN 120 AND 288 WHERE YEAR(`tbl_gbv_vacs`.date_of_event) = YEAR(t1.date_of_event) AND `tbl_gbv_vacs`.violence_category_id = t2.id) AS female_under_24,
    (SELECT COUNT(gender) FROM `tbl_gbv_vacs` INNER JOIN `tbl_patients`
        ON `tbl_gbv_vacs`.patient_id = 	`tbl_patients`.id AND `tbl_patients`.gender = 'Male'
            AND TIMESTAMPDIFF(MONTH, `tbl_patients`.dob, CURRENT_DATE)  BETWEEN 120 AND 288 WHERE YEAR(`tbl_gbv_vacs`.date_of_event) = YEAR(t1.date_of_event) AND `tbl_gbv_vacs`.violence_category_id = t2.id) AS male_under_24,
    (SELECT COUNT(gender) FROM `tbl_gbv_vacs` INNER JOIN `tbl_patients`
        ON `tbl_gbv_vacs`.patient_id = 	`tbl_patients`.id
            AND TIMESTAMPDIFF(MONTH, `tbl_patients`.dob, CURRENT_DATE)  BETWEEN 120 AND 288 WHERE YEAR(`tbl_gbv_vacs`.date_of_event) = YEAR(t1.date_of_event) AND `tbl_gbv_vacs`.violence_category_id = t2.id) AS total_under_24,

        (SELECT COUNT(gender) FROM `tbl_gbv_vacs` INNER JOIN `tbl_patients`
        ON `tbl_gbv_vacs`.patient_id = 	`tbl_patients`.id AND `tbl_patients`.gender = 'Female'
            AND TIMESTAMPDIFF(MONTH, `tbl_patients`.dob, CURRENT_DATE)  >=300 WHERE YEAR(`tbl_gbv_vacs`.date_of_event) = YEAR(t1.date_of_event) AND `tbl_gbv_vacs`.violence_category_id = t2.id) AS female_above_25,
    (SELECT COUNT(gender) FROM `tbl_gbv_vacs` INNER JOIN `tbl_patients`
        ON `tbl_gbv_vacs`.patient_id = 	`tbl_patients`.id AND `tbl_patients`.gender = 'Male'
            AND TIMESTAMPDIFF(MONTH, `tbl_patients`.dob, CURRENT_DATE)   >=300 WHERE YEAR(`tbl_gbv_vacs`.date_of_event) = YEAR(t1.date_of_event) AND `tbl_gbv_vacs`.violence_category_id = t2.id) AS male_above_25,
    (SELECT COUNT(gender) FROM `tbl_gbv_vacs` INNER JOIN `tbl_patients`
        ON `tbl_gbv_vacs`.patient_id = 	`tbl_patients`.id
            AND TIMESTAMPDIFF(MONTH, `tbl_patients`.dob, CURRENT_DATE)  >=300 WHERE YEAR(`tbl_gbv_vacs`.date_of_event) = YEAR(t1.date_of_event) AND `tbl_gbv_vacs`.violence_category_id = t2.id) AS total_above_25,

        (SELECT COUNT(gender) FROM `tbl_gbv_vacs` INNER JOIN `tbl_patients`
        ON `tbl_gbv_vacs`.patient_id = 	`tbl_patients`.id AND `tbl_patients`.gender = 'Female' WHERE YEAR(`tbl_gbv_vacs`.date_of_event AND `tbl_gbv_vacs`.violence_category_id = t2.id) = YEAR(t1.date_of_event)) AS total_female,
    (SELECT COUNT(gender) FROM `tbl_gbv_vacs` INNER JOIN `tbl_patients`
        ON `tbl_gbv_vacs`.patient_id = 	`tbl_patients`.id AND `tbl_patients`.gender = 'Male' WHERE YEAR(`tbl_gbv_vacs`.date_of_event AND `tbl_gbv_vacs`.violence_category_id = t2.id) = YEAR(t1.date_of_event)) AS total_male,
    (SELECT COUNT(gender) FROM `tbl_gbv_vacs` INNER JOIN `tbl_patients`
        ON `tbl_gbv_vacs`.patient_id = 	`tbl_patients`.id WHERE YEAR(`tbl_gbv_vacs`.date_of_event) = YEAR(t1.date_of_event) AND `tbl_gbv_vacs`.violence_category_id = t2.id) AS total

        FROM `tbl_gbv_vacs` AS t1 INNER JOIN `tbl_violence_categories`   AS t2 ON t1.violence_category_id =t2.id GROUP BY year, violence_type_category,date_of_event,facility_id,t2.id






                         ");

    }

public static function vw_child_attendance(){
    DB::statement("
   CREATE or REPLACE VIEW vw_child_registers AS SELECT facility_id,created_at, SUM(CASE WHEN gender = 'male' THEN 1 ELSE 0 END) as Male_count, SUM(CASE WHEN gender = 'Female' THEN 1 ELSE 0 END) as Female_count,
     count(gender) as total_gender FROM tbl_child_registers GROUP BY facility_id,created_at
     ");
}

 

public static function vw_child_feedings(){
    DB::statement("CREATE or REPLACE VIEW vw_baby_feedings as SELECT tbl_child_feedings.created_at,tbl_child_feedings.facility_id,sum(CASE WHEN gender='MALE' AND feeding_type='EBF' THEN 1 else 0 END) as ebf_male,sum(CASE WHEN gender='FEMALE' AND feeding_type='EBF' THEN 1 else 0 END) as ebf_female,sum(CASE WHEN  feeding_type='EBF' THEN 1 else 0 END) as ebf_total,sum(CASE WHEN gender='MALE' AND feeding_type='RF' THEN 1 else 0 END) as rf_male,sum(CASE WHEN gender='FEMALE' AND feeding_type='RF' THEN 1 else 0 END) as rf_female,sum(CASE WHEN  feeding_type='RF' THEN 1 else 0 END) as rf_total from tbl_child_feedings INNER JOIN tbl_child_registers on 
    tbl_child_registers.id=tbl_child_feedings.patient_id GROUP by tbl_child_feedings.created_at,tbl_child_feedings.facility_id
     ");
}

 public static function vw_exemption_finance(){
    DB::statement(" CREATE or REPLACE view vw_exemption_finance as SELECT t1.id as item_id ,t4.item_name,t8.exemption_reason,t9.mobile_number,Concat(t9.first_name,' ',t9.middle_name,' ',t9.last_name,' ',t9.medical_record_number) as names,
         t5.department_name, t5.id as dept_id,t1.*,t2.price,t8.created_at as date,t6.sub_category_name,t6.id as patient_cat_id,t7.gender,t7.dob
         
         FROM 
         tbl_invoice_lines t1, tbl_item_prices t2,tbl_item_type_mappeds t3,tbl_items t4,tbl_departments t5,tbl_pay_cat_sub_categories t6,
         tbl_patients t7,tbl_exemptions t8,tbl_patients t9
         WHERE t2.id =t1.item_price_id 
         AND t3.id=t1.item_type_id
         AND t4.id=t3.item_id
         AND t5.id=t4.dept_id 
         AND t6.id=t8.exemption_type_id
          AND t1.patient_id=t7.id
          AND t1.patient_id=t9.id 
          
          ");
    
}
public static function vw_exemption_finance_depts(){
    DB::statement(" CREATE or REPLACE view vw_exemption_finance_depts as SELECT t5.department_name,t1.facility_id,t1.created_at,t1.id, SUM((price * quantity)-t1.discount) AS total,COUNT(t1.id) AS idadi FROM tbl_invoice_lines t1,tbl_pay_cat_sub_categories t6, tbl_item_prices t2,tbl_item_type_mappeds t3,tbl_items t4,tbl_departments t5 WHERE t2.id =t1.item_price_id AND t1.status_id=1 AND t3.id=t1.item_type_id AND
     t4.id=t3.item_id AND t5.id=t4.dept_id AND t6.id = t1.payment_filter AND t6.pay_cat_id = 3
      GROUP BY t5.department_name,t1.facility_id
          
          ");

}

 public static function vw_exemption_servce_summary(){
        DB::statement("  CREATE OR REPLACE VIEW `vw_exemption_service_summary` AS SELECT 					
					tbl_invoice_lines.id,
					tbl_invoice_lines.id AS item_refference,
					tbl_encounter_invoices.id AS receipt_number,					
					tbl_invoice_lines.invoice_id,
					tbl_invoice_lines.patient_id,
					tbl_patients.medical_record_number,
					tbl_patients.dob,
					tbl_patients.gender,
					tbl_patients.first_name,
					tbl_patients.middle_name,
					tbl_patients.last_name,
					tbl_patients.mobile_number,					
					tbl_invoice_lines.quantity, 
	                tbl_invoice_lines.discount, 
	                tbl_invoice_lines.status_id, 
	                tbl_invoice_lines.facility_id,
	                tbl_invoice_lines.item_type_id,
	                tbl_invoice_lines.discount_by,
	                tbl_invoice_lines.payment_filter,
	                tbl_invoice_lines.payment_filter as pay_cat_id,
	                tbl_invoice_lines.gepg_receipt,
	                tbl_invoice_lines.payment_method_id,
	                tbl_pay_cat_sub_categories.sub_category_name,
	                tbl_payments_categories.category_description,
	                tbl_payments_categories.id AS main_category_id,
	                tbl_invoice_lines.created_at,
	                tbl_item_prices.price,
	                tbl_item_prices.price AS unit_price,
	                tbl_items.item_name, 
                    tbl_departments.department_name, 
                    tbl_departments.id as dept_id, 
	                tbl_item_type_mappeds.item_category,
	                tbl_accounts_numbers.account_number,
                    tbl_exemptions.exemption_reason,
     
	                tbl_payment_statuses.payment_status 
	FROM tbl_invoice_lines INNER JOIN tbl_item_type_mappeds ON tbl_invoice_lines.item_type_id = tbl_item_type_mappeds.id 
	INNER JOIN tbl_item_prices ON tbl_invoice_lines.item_price_id = tbl_item_prices.ID
	INNER JOIN tbl_items ON tbl_item_prices.item_id = tbl_items.id
    INNER JOIN tbl_exemptions ON tbl_invoice_lines.payment_filter = tbl_exemptions.exemption_type_id
    INNER JOIN tbl_departments ON tbl_items.dept_id = tbl_departments.id 
	INNER JOIN tbl_payment_statuses ON tbl_invoice_lines.status_id = tbl_payment_statuses.ID 
	INNER JOIN tbl_encounter_invoices ON tbl_invoice_lines.invoice_id = tbl_encounter_invoices.id 
	INNER JOIN tbl_accounts_numbers ON tbl_encounter_invoices.account_number_id = tbl_accounts_numbers.ID 
	INNER JOIN tbl_patients ON tbl_accounts_numbers.patient_id = tbl_patients.id 					
	INNER JOIN tbl_pay_cat_sub_categories ON tbl_invoice_lines.payment_filter = tbl_pay_cat_sub_categories.id 					
	INNER JOIN tbl_payments_categories ON tbl_pay_cat_sub_categories.pay_cat_id = tbl_payments_categories.id 
					group by item_name,tbl_invoice_lines.patient_id order by tbl_invoice_lines.id asc
					
          ");

    }
	//creates all views required by mtuha
	public static function mtuhaViews(){
        $admitted="CREATE OR REPLACE VIEW vw_admission_register AS(SELECT tbl_admission_registers.facility_id, tbl_admission_registers.date, ifnull(male_under_one_month,0) as male_under_one_month,ifnull(female_under_one_month,0) as female_under_one_month, ifnull(total_under_one_month,0) as total_under_one_month,ifnull(male_under_one_year,0) as male_under_one_year,ifnull(female_under_one_year,0) as female_under_one_year,ifnull(total_under_one_year,0) as total_under_one_year,ifnull(male_under_five_year,0) as male_under_five_year,ifnull(female_under_five_year,0) as female_under_five_year,ifnull(total_under_five_year,0) as total_under_five_year,ifnull(male_above_five_under_sixty,0) as male_above_five_under_sixty,ifnull(female_above_five_under_sixty,0) as female_above_five_under_sixty,ifnull(total_above_five_under_sixty,0) as total_above_five_under_sixty,ifnull(male_above_sixty,0) as male_above_sixty,ifnull(female_above_sixty,0) as female_above_sixty,ifnull(total_above_sixty,0) as total_above_sixty,ifnull(total_male,0) as total_male,ifnull(total_female,0) as total_female,ifnull(grand_total,0) as grand_total FROM tbl_admission_registers)";

		DB::statement($admitted);
		
		$new_attendance="CREATE OR REPLACE VIEW vw_newattendance_register  AS (SELECT tbl_newattendance_registers.facility_id, tbl_newattendance_registers.clinic_id, tbl_newattendance_registers.date, ifnull(male_under_one_month,0) as male_under_one_month,ifnull(female_under_one_month,0) as female_under_one_month, ifnull(total_under_one_month,0) as total_under_one_month,ifnull(male_under_one_year,0) as male_under_one_year,ifnull(female_under_one_year,0) as female_under_one_year,ifnull(total_under_one_year,0) as total_under_one_year,ifnull(male_under_five_year,0) as male_under_five_year,ifnull(female_under_five_year,0) as female_under_five_year,ifnull(total_under_five_year,0) as total_under_five_year,ifnull(male_above_five_under_sixty,0) as male_above_five_under_sixty,ifnull(female_above_five_under_sixty,0) as female_above_five_under_sixty,ifnull(total_above_five_under_sixty,0) as total_above_five_under_sixty,ifnull(male_above_sixty,0) as male_above_sixty,ifnull(female_above_sixty,0) as female_above_sixty,ifnull(total_above_sixty,0) as total_above_sixty,ifnull(total_male,0) as total_male,ifnull(total_female,0) as total_female,ifnull(grand_total,0) as grand_total FROM tbl_newattendance_registers)";

		DB::statement($new_attendance);
		
		$reattendance="CREATE OR REPLACE VIEW vw_reattendance_register  AS (SELECT tbl_reattendance_registers.facility_id, tbl_reattendance_registers.clinic_id, tbl_reattendance_registers.date, ifnull(male_under_one_month,0) as male_under_one_month,ifnull(female_under_one_month,0) as female_under_one_month, ifnull(total_under_one_month,0) as total_under_one_month,ifnull(male_under_one_year,0) as male_under_one_year,ifnull(female_under_one_year,0) as female_under_one_year,ifnull(total_under_one_year,0) as total_under_one_year,ifnull(male_under_five_year,0) as male_under_five_year,ifnull(female_under_five_year,0) as female_under_five_year,ifnull(total_under_five_year,0) as total_under_five_year,ifnull(male_above_five_under_sixty,0) as male_above_five_under_sixty,ifnull(female_above_five_under_sixty,0) as female_above_five_under_sixty,ifnull(total_above_five_under_sixty,0) as total_above_five_under_sixty,ifnull(male_above_sixty,0) as male_above_sixty,ifnull(female_above_sixty,0) as female_above_sixty,ifnull(total_above_sixty,0) as total_above_sixty,ifnull(total_male,0) as total_male,ifnull(total_female,0) as total_female,ifnull(grand_total,0) as grand_total FROM tbl_reattendance_registers)";

		DB::statement($reattendance);
		
		$ipd_diagnosis="CREATE OR REPLACE VIEW vw_ipd_diagnosis_register AS(SELECT tbl_ipd_diseases_registers.ipd_mtuha_diagnosis_id as diagnosis_id,tbl_ipd_diseases_registers.facility_id, tbl_ipd_diseases_registers.date, tbl_ipd_mtuha_diagnoses.description,ifnull(sum(male_under_one_month),0) as male_under_one_month,ifnull(sum(female_under_one_month),0) as female_under_one_month, ifnull(sum(total_under_one_month),0) as total_under_one_month,ifnull(sum(male_under_one_year),0) as male_under_one_year,ifnull(sum(female_under_one_year),0) as female_under_one_year,ifnull(sum(total_under_one_year),0) as total_under_one_year,ifnull(sum(male_under_five_year),0) as male_under_five_year,ifnull(sum(female_under_five_year),0) as female_under_five_year,ifnull(sum(total_under_five_year),0) as total_under_five_year,ifnull(sum(male_above_five_under_sixty),0) as male_above_five_under_sixty,ifnull(sum(female_above_five_under_sixty),0) as female_above_five_under_sixty,ifnull(sum(total_above_five_under_sixty),0) as total_above_five_under_sixty,ifnull(sum(male_above_sixty),0) as male_above_sixty,ifnull(sum(female_above_sixty),0) as female_above_sixty,ifnull(sum(total_above_sixty),0) as total_above_sixty,ifnull(sum(total_male),0) as total_male,ifnull(sum(total_female),0) as total_female,ifnull(sum(grand_total),0) as grand_total FROM tbl_ipd_mtuha_diagnoses LEFT JOIN tbl_ipd_diseases_registers on tbl_ipd_mtuha_diagnoses.id = tbl_ipd_diseases_registers.ipd_mtuha_diagnosis_id group by tbl_ipd_diseases_registers.facility_id, tbl_ipd_diseases_registers.ipd_mtuha_diagnosis_id, tbl_ipd_diseases_registers.date ORDER BY tbl_ipd_mtuha_diagnoses.id)";
        DB::statement($ipd_diagnosis);
		
        $opd_diagnosis="CREATE OR REPLACE VIEW vw_opd_diagnosis_register AS(SELECT tbl_opd_diseases_registers.opd_mtuha_diagnosis_id as diagnosis_id,tbl_opd_diseases_registers.facility_id, tbl_opd_diseases_registers.date, tbl_opd_mtuha_diagnoses.description,ifnull(sum(male_under_one_month),0) as male_under_one_month,ifnull(sum(female_under_one_month),0) as female_under_one_month, ifnull(sum(total_under_one_month),0) as total_under_one_month,ifnull(sum(male_under_one_year),0) as male_under_one_year,ifnull(sum(female_under_one_year),0) as female_under_one_year,ifnull(sum(total_under_one_year),0) as total_under_one_year,ifnull(sum(male_under_five_year),0) as male_under_five_year,ifnull(sum(female_under_five_year),0) as female_under_five_year,ifnull(sum(total_under_five_year),0) as total_under_five_year,ifnull(sum(male_above_five_under_sixty),0) as male_above_five_under_sixty,ifnull(sum(female_above_five_under_sixty),0) as female_above_five_under_sixty,ifnull(sum(total_above_five_under_sixty),0) as total_above_five_under_sixty,ifnull(sum(male_above_sixty),0) as male_above_sixty,ifnull(sum(female_above_sixty),0) as female_above_sixty,ifnull(sum(total_above_sixty),0) as total_above_sixty,ifnull(sum(total_male),0) as total_male,ifnull(sum(total_female),0) as total_female,ifnull(sum(grand_total),0) as grand_total FROM tbl_opd_mtuha_diagnoses LEFT JOIN tbl_opd_diseases_registers on tbl_opd_mtuha_diagnoses.id = tbl_opd_diseases_registers.opd_mtuha_diagnosis_id group by tbl_opd_diseases_registers.facility_id, tbl_opd_diseases_registers.opd_mtuha_diagnosis_id, tbl_opd_diseases_registers.date ORDER BY tbl_opd_mtuha_diagnoses.id)";

		DB::statement($opd_diagnosis);
	 }
}