select ct.id, ct.cod_seccion, ct.id_operador, ct.tema, ct.estado, c.id_usuario_de as id_usu_ult, c.fecha_envio as fec_ult, max(c.id) as id_conv, u.descripcion_usuario
from tb_chat_tema ct, tb_usuario_operador uo, tb_chat c, tb_usuarios u
where uo.id_operador = ct.id_operador
and ct.id = c.id_tema
and c.id_usuario_de = u.id_usuario
and uo.id_usuario = 6 and ct.estado = 'ABIERTO'
group by ct.id
union
select ct.id, ct.cod_seccion, ct.id_operador, ct.tema, ct.estado, ct.id_usuario as id_usu_ult, ct.fecha_creacion as fec_ult, ct.id as id_conv, u.descripcion_usuario  
from tb_chat_tema ct
left join tb_chat c
	on ct.id = c.id_tema	
join tb_usuario_operador uo
	on uo.id_operador = ct.id_operador
join tb_usuarios u
	on ct.id_usuario = u.id_usuario
where c.id_tema is null
and uo.id_usuario = 6
and ct.estado = 'ABIERTO'
order by fec_ult desc
;