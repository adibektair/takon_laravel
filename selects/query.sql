select t.id,
       case
         when t.type = 1
                 then case
                        when company_sender.id is not null then company_sender.phone
                        when mobile_user_sender.id is not null then mobile_user_sender.phone
           end
         when t.type = 2
                 then p.phone
         when t.type = 3
                 then mobile_user_sender.phone
         when t.type = 4
                 then p.phone
         when t.type = 5
                 then mobile_user_sender.phone
           end sender,
       case
         when t.type = 1
                 then case
                        when company_sender.id is not null then company_sender.name
                        when mobile_user_sender.id is not null then mobile_user_sender.name
           end
         when t.type = 2
                 then p.name
         when t.type = 3
                 then mobile_user_sender.name
         when t.type = 4
                 then p.name
         when t.type = 5
                 then mobile_user_sender.phone
           end sender_name,
       s.name  service_name,
       case
         when t.type in (1, 3)
                 then t.amount
           end sent,
       case
         when t.type in (2)
                 then t.amount
         when t.type in (4, 5)
                 then t.amount
           end recevied,
       t.created_at
from transactions t
       inner join companies_services cs on t.cs_id = cs.id
       inner join services s on cs.service_id = s.id
       inner join companies c on cs.company_id = c.id
       left join partners p on t.p_s_id = p.id
       left join mobile_users mobile_user_receiver on mobile_user_receiver.id = t.u_r_id
       left join users cashier_user_receiver on cashier_user_receiver.id = t.u_r_id
       left join companies company_receiver on company_receiver.id = t.c_r_id
       left join mobile_users mobile_user_sender on mobile_user_sender.id = t.u_s_id
       left join companies company_sender on company_sender.id = t.c_s_id

WHERE c.id = 8
#   and t.created_at between '2019-09-01' and '2019-09-30'
    # group by s.id
order by t.created_at asc;