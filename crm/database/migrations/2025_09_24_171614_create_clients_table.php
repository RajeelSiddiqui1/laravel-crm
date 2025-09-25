<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('client_detail', function (Blueprint $table) {
            $table->bigIncrements('id');

            // Personal
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('set null');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('telephone')->nullable();
            $table->string('email')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('sin')->nullable();
            $table->text('address')->nullable();
            $table->text('mailing_address')->nullable();
            $table->string('marital_status')->nullable();
            $table->string('status_in_canada')->nullable();
            $table->string('ids_driving_passport')->nullable();
            $table->date('ids_expiry_date')->nullable();
            $table->string('education')->nullable();

            // Corporation
            $table->string('corporation_registered_name')->nullable();
            $table->string('fiscal_year_t2')->nullable();
            $table->string('ontario_corporation_no')->nullable();
            $table->string('fiscal_year_hst')->nullable();
            $table->string('business_no')->nullable();
            $table->text('business_activities')->nullable();
            $table->date('date_of_corporation')->nullable();
            $table->string('corporation_key')->nullable();
            $table->string('register_in_cra_for')->nullable();
            $table->text('business_address')->nullable();
            $table->string('corporation_website')->nullable();
            $table->string('corporation_type')->nullable();
            $table->string('ontario_business_corporation_partnership')->nullable();

            // Financial Institutions & Payments
            $table->string('financial_institutions')->nullable();
            $table->string('account_no_void_cheque')->nullable();
            $table->string('credit_card_nos_from')->nullable();
            $table->string('outstanding_balance')->nullable();

            // Loans & Mortgage
            $table->text('loans_from_institutions')->nullable();
            $table->string('loan_outstanding_balance_installment')->nullable();
            $table->string('mortgage_from')->nullable();
            $table->string('mortgage_outstanding_balance_installment')->nullable();

            // Automotive
            $table->string('auto_make_year')->nullable();
            $table->string('lease_or_loan')->nullable();

            // WSIB
            $table->string('wsib_account_no')->nullable();

            // Other
            $table->string('client_introduced_by')->nullable();
            $table->string('category')->nullable();
            $table->string('lmia_work_permit_from')->nullable();

            // Service charges / fees (monetary fields)
            $table->decimal('service_charges_fees', 14, 2)->nullable();
            $table->decimal('bookkeeping', 14, 2)->nullable();
            $table->decimal('corporation_tax', 14, 2)->nullable();
            $table->decimal('hst', 14, 2)->nullable();
            $table->decimal('financials', 14, 2)->nullable();
            $table->decimal('personal_tax', 14, 2)->nullable();
            $table->decimal('immigration', 14, 2)->nullable();
            $table->decimal('corporation_registration', 14, 2)->nullable();
            $table->decimal('accounting', 14, 2)->nullable();

            // Signatures (store as string paths or signer names; adjust as needed)
            $table->string('mh_enterprises_signature')->nullable();
            $table->string('client_signature')->nullable();

            $table->timestamps();
            $table->softDeletes(); // optional: keep if you want soft deletes
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
}
