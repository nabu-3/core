select * from nb_site_target where nb_site_id=1
;

select * from nb_site_target_lang where nb_site_target_id in (select nb_site_target_id from nb_site_target where nb_site_id=1)
;

select * from nb_site_target_section where nb_site_target_id in (select nb_site_target_id from nb_site_target where nb_site_id=1)
;

select * from nb_site_target_section_lang where nb_site_target_section_id in (select nb_site_target_section_id from nb_site_target_section where nb_site_target_id in (select nb_site_target_id from nb_site_target where nb_site_id=1))
;

select * from nb_site_target_cta where nb_site_target_id in (select nb_site_target_id from nb_site_target where nb_site_id=1)
;

select * from nb_site_target_cta_lang where nb_site_target_cta_id in (select nb_site_target_cta_id from nb_site_target_cta where nb_site_target_id in (select nb_site_target_id from nb_site_target where nb_site_id=1))
;

select * from nb_site_target_cta_role where nb_site_target_cta_id in (select nb_site_target_cta_id from nb_site_target_cta where nb_site_target_id in (select nb_site_target_id from nb_site_target where nb_site_id=1))
;

select * from nb_site_static_content where nb_site_id=1
;

select * from nb_site_static_content_lang where nb_site_static_content_id in (select nb_site_static_content_id from nb_site_static_content where nb_site_id=1)
;

select * from nb_site_map where nb_site_id=1
;

select * from nb_site_map_lang where nb_site_map_id in (select nb_site_map_id from nb_site_map where nb_site_id=1)
;

select * from nb_site_map_role where nb_site_map_id in (select nb_site_map_id from nb_site_map where nb_site_id=1)
;