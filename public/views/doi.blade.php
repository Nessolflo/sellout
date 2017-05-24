-- --------------------------------------------------------------------------------
-- Routine DDL
-- Note: comments before and after the routine body will not be stored by the server
-- --------------------------------------------------------------------------------
DELIMITER $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `DOI`(IN `semanainicio` INT,IN `semanafin` INT,IN `aniobq` INT,IN `sucursal` INT)
    READS SQL DATA
BEGIN
	
	 select 		
        
		sum(IF(vistaventas.semana=semanafin,vistaventas.inventory,0)) AS inventory,
		sum(vistaventas.sellout)  AS sellout,
		round((sum(vistaventas.sellout)/5)/7,2)					AS pdS,
		round(sum(IF(vistaventas.semana=semanafin,vistaventas.inventory,0))/	round((sum(vistaventas.sellout)/5)/7,2)	)			AS DOI,
		vistaventas.modelo as modelo,
		vistaventas.sucursal as sucursal
		-- semanainicio 		AS semanai,
		-- semanafin 		AS semanaf,
		-- round((SUM(vistaventas.inventory) /SUM( vistaventas.sellout))*((semanafin-semanainicio+1)*5)) AS doi,
        -- vistaventas.anio as	anio,
		-- vistaventas.sucursal as nombresucursal		
    from
       vistaventas
	where
		vistaventas.inventory > 1 and vistaventas.sellout> 0 and  `vistaventas`.`semana` BETWEEN semanainicio AND semanafin
		and vistaventas.anio = aniobq and  vistaventas.idsucursal = sucursal  
		group by vistaventas.modelo
		order by sellout desc
	;
	
		
END