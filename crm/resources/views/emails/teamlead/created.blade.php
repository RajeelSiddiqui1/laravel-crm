@component('mail::message')
# 🎉 Welcome Aboard, {{ $teamLead->name }}!

We’re thrilled to have you as part of our project team.  
Your account has been created by **{{ $manager->name }}**, Project Manager.

---

### 👤 Your Account Details:
- **Email:** {{ $teamLead->email }}
- **Temporary Password:** `000`
- **Phone:** {{ $teamLead->phone }}
- **Department ID:** {{ $teamLead->department_id }}

Please log in and change your password for security.

---

@component('mail::button', ['url' => url('/teamlead/login')])
Login to Dashboard
@endcomponent

Thank you for joining us — we look forward to great things together! 🚀  

Warm regards,  
**{{ $manager->name }}**  
_Project Management Team_
@endcomponent
