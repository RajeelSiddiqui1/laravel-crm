<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClientDetail extends Model
{

       protected $table = 'client_details';
      protected $fillable = [
        'employee_id',
        'last_name',
        'first_name',
        'telephone',
        'status',
        'email',
        'date_of_birth',
        'sin',
        'address',
        'mailing_address',
        'marital_status',
        'status_in_canada',
        'ids_driving_passport',
        'ids_expiry_date',
        'education',
        'corporation_registered_name',
        'fiscal_year_t2',
        'ontario_corporation_no',
        'fiscal_year_hst',
        'business_no',
        'business_activities',
        'date_of_corporation',
        'corporation_key',
        'register_in_cra_for',
        'business_address',
        'corporation_website',
        'corporation_type',
        'ontario_business_corporation_partnership',
        'financial_institutions',
        'account_no_void_cheque',
        'credit_card_nos_from',
        'outstanding_balance',
        'loans_from_institutions',
        'loan_outstanding_balance_installment',
        'mortgage_from',
        'mortgage_outstanding_balance_installment',
        'auto_make_year',
        'lease_or_loan',
        'wsib_account_no',
        'client_introduced_by',
        'category',
        'lmia_work_permit_from',
        'service_charges_fees',
        'bookkeeping',
        'corporation_tax',
        'hst',
        'financials',
        'personal_tax',
        'immigration',
        'corporation_registration',
        'accounting',
        'mh_enterprises_signature',
        'client_signature',
    ];

    /**
     * Relationships
     */

    // Client belongs to one employee

     public function subtask(): BelongsTo
    {
        return $this->belongsTo(Subtask::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
