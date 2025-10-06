@component('mail::message')
# 👋 Welcome to the Company, {{ $employee->name }}!

We’re excited to have you on our team.  
You’ve been added to the system by **{{ $manager->name }}**, your Project Manager.

---

### 🧾 Account Details
- **Email:** {{ $employee->email }}
- **Temporary Password:** `000`
- **Department ID:** {{ $employee->department_id }}
- **Phone:** {{ $employee->phone }}

Please log in to your account and update your password immediately.

---

@component('mail::button', ['url' => url('/employee/login')])
Access Your Dashboard
@endcomponent

Your hard work and creativity will help us grow together. Welcome aboard! 🚀  

Warm Regards,  
**{{ $manager->name }}**  
_Project Management Team_
@endcomponent
