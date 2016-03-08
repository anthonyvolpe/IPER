<?php
/**
 * Created by PhpStorm.
 * User: Fabrizio Pera
 * Company: Iperdesign SNC
 * URL: http://www.iperdesign.com/it/
 * Date: 12/02/16
 * Time: 20:54
 */



global $wpdb;

    
$dbNAME = DB_NAME;    
    
$sql =' CREATE DEFINER=`'.$dbNAME.'`@`%` PROCEDURE `IPER_MA_ACCESSORY_GROUP_GET` (IN `__idACCESSORY_GROUP` BIGINT(20))  BEGIN SELECT * FROM '.$dbNAME.'.iper_MA_ACCESSORY_GROUP where idACCESSORY_GROUP=__idACCESSORY_GROUP and META_REQUEST_ID = IPER_MA_FUNC_LAST_RESPONSE_ID() ; END';

$wpdb->query($sql);

$sql .='
CREATE DEFINER=`'.$dbNAME.'`@`%` PROCEDURE `IPER_MA_ACCESSORY_GROUP_LIST` (`__fkRATEPLAN` BIGINT(20))  BEGIN
SELECT * FROM '.$dbNAME.'.iper_MA_ACCESSORY_GROUP where fkRATEPLAN=__fkRATEPLAN and META_REQUEST_ID = IPER_MA_FUNC_LAST_RESPONSE_ID() ;
END$$

CREATE DEFINER=`'.$dbNAME.'`@`%` PROCEDURE `IPER_MA_ACCESSORY_GROUP_SET` (`__idACCESSORY_GROUP` BIGINT(20), `__fkRATEPLAN` BIGINT(20), `__LimitRestriction` LONGTEXT, `__IncludedWithOrder` LONGTEXT, `__Description` LONGTEXT, `__AccessoryGroupID` LONGTEXT, `__META_WP_ID` BIGINT(20), `__META_REQUEST_ID` BIGINT(20))  BEGIN


if(__idACCESSORY_GROUP = 0) then
	select ifnull(META_WP_ID,0) ,ifnull(idACCESSORY_GROUP,0) into __META_WP_ID ,__idACCESSORY_GROUP from iper_MA_ACCESSORY_GROUP where AccessoryGroupID = __AccessoryGroupID and fkRATEPLAN = __fkRATEPLAN;
end if;


if(__idACCESSORY_GROUP <> 0 ) then


	UPDATE`iper_MA_ACCESSORY_GROUP`
	SET
	`LimitRestriction` = __LimitRestriction,
	`IncludedWithOrder` = __IncludedWithOrder,
	`Description` = __Description,
	`META_WP_ID` = __META_WP_ID,
	`META_DATE_UPDATE` =now(),
	`META_REQUEST_ID` = __META_REQUEST_ID
	WHERE `idACCESSORY_GROUP` = __idACCESSORY_GROUP;



    call IPER_META_SHOWRESUL(1,__idACCESSORY_GROUP);

else

	INSERT INTO `iper_MA_ACCESSORY_GROUP`
	(
    `fkRATEPLAN`,
	`LimitRestriction`,
	`IncludedWithOrder`,
	`Description`,
	`AccessoryGroupID`,
	`META_WP_ID`,
	`META_DATE_INSERT`,
	`META_REQUEST_ID`)
	VALUES
    (
        __fkRATEPLAN,
        __LimitRestriction,
        __IncludedWithOrder,
        __Description,
        __AccessoryGroupID,
        __META_WP_ID,
        now(),
        __META_REQUEST_ID);




	call IPER_META_SHOWRESUL(2,@@IDENTITY);


end if;
END$$

CREATE DEFINER=`'.$dbNAME.'`@`%` PROCEDURE `IPER_MA_ACCESSORY_LIST` (`__fkRATEPLAN` BIGINT(20), `__fkACCESSORY_GROUP` BIGINT(20))  BEGIN

if (__fkACCESSORY_GROUP<>0) then

	SELECT fkACCESSORY_GROUP , '.$dbNAME.'.iper_MA_ACCESSORY.* FROM iper_MA_ACCESSORY_GROUP_ACCESSORY join '.$dbNAME.'.iper_MA_ACCESSORY on(fkACCESSORY = idACCESSORY)
	where fkACCESSORY_GROUP = __fkACCESSORY_GROUP and iper_MA_ACCESSORY.META_REQUEST_ID = IPER_MA_FUNC_LAST_RESPONSE_ID()  ;

else

	SELECT fkACCESSORY_GROUP , '.$dbNAME.'.iper_MA_ACCESSORY.* FROM iper_MA_ACCESSORY_GROUP join iper_MA_ACCESSORY_GROUP_ACCESSORY on(idACCESSORY_GROUP = fkACCESSORY_GROUP) join '.$dbNAME.'.iper_MA_ACCESSORY on(fkACCESSORY = idACCESSORY)
	where fkRATEPLAN = __fkRATEPLAN and iper_MA_ACCESSORY.META_REQUEST_ID = IPER_MA_FUNC_LAST_RESPONSE_ID()  ;


end if;
END$$

CREATE DEFINER=`'.$dbNAME.'`@`%` PROCEDURE `IPER_MA_ACCESSORY_SET` (`__fkACCESSORY_GROUP` BIGINT(20), `__idACCESSORY` BIGINT(20), `__Quantity` LONGTEXT, `__Price` LONGTEXT, `__IsAvailable` LONGTEXT, `__AccessoryName` LONGTEXT, `__AccessoryID` LONGTEXT, `__AccessoryCode` LONGTEXT, `__META_WP_ID` BIGINT(20), `__META_REQUEST_ID` BIGINT(20))  BEGIN
declare __c int ;
set __c = 0;

if(__META_WP_ID = 0) then
	select ifnull(META_WP_ID,0) ,ifnull(idACCESSORY,0),ifnull(AccessoryCode,0) into __META_WP_ID ,__idACCESSORY,__AccessoryCode from iper_MA_ACCESSORY where AccessoryID = __AccessoryID and AccessoryCode = __AccessoryCode;
end if;


if(__META_WP_ID <> 0 ) then


	UPDATE `iper_MA_ACCESSORY`
	SET
	`Quantity` = __Quantity,
	`Price` = __Price,
	`IsAvailable` = __IsAvailable,
	`AccessoryName` = __AccessoryName,
	`AccessoryCode` = __AccessoryCode,
	`META_WP_ID` = __META_WP_ID,
	`META_DATE_UPDATE` = now(),
	`META_REQUEST_ID` = __META_REQUEST_ID
	WHERE `idACCESSORY` = __idACCESSORY ;



	select  ifnull(count(*),0) into __c from `iper_MA_ACCESSORY_GROUP_ACCESSORY` where fkACCESSORY_GROUP=__fkACCESSORY_GROUP and fkACCESSORY= __idACCESSORY;
    
    
    if(__c=0) then
		
        INSERT INTO `iper_MA_ACCESSORY_GROUP_ACCESSORY`
		(`fkACCESSORY_GROUP`,
		`fkACCESSORY`,
		`Price`,
		`META_DATE_INSERT`,
		`META_REQUEST_ID`)
		VALUES
        (__fkACCESSORY_GROUP,
            __idACCESSORY,
            __Price,
            now(),
            __META_REQUEST_ID);
    
    else
    
		UPDATE `'.$dbNAME.'`.`iper_MA_ACCESSORY_GROUP_ACCESSORY`
		SET
		
		`Price` = __Price,
		`META_DATE_UPDATE` = now(),
		`META_REQUEST_ID` = __META_REQUEST_ID
		WHERE `fkACCESSORY_GROUP` = __fkACCESSORY_GROUP AND `fkACCESSORY` = __idACCESSORY;

    
    end if;





    call IPER_META_SHOWRESUL(1,__idACCESSORY);

else

	INSERT INTO `iper_MA_ACCESSORY`
	(
    `Quantity`,
	`Price`,
	`IsAvailable`,
	`AccessoryName`,
	`AccessoryID`,
	`AccessoryCode`,
	`META_WP_ID`,
	`META_DATE_INSERT`,
	`META_REQUEST_ID`)
	VALUES
    (
        __Quantity,
        __Price,
        __IsAvailable,
        __AccessoryName,
        __AccessoryID,
        __AccessoryCode,
        __META_WP_ID,
        now(),
        __META_REQUEST_ID);

	set __idACCESSORY = @@IDENTITY;


	INSERT INTO `iper_MA_ACCESSORY_GROUP_ACCESSORY`
	(`fkACCESSORY_GROUP`,
	`fkACCESSORY`,
	`Price`,
	`META_DATE_INSERT`,
	`META_REQUEST_ID`)
	VALUES
    (__fkACCESSORY_GROUP,
        __idACCESSORY,
        __Price,
        now(),
        __META_REQUEST_ID);




	call IPER_META_SHOWRESUL(2,__idACCESSORY);


end if;
END$$

CREATE DEFINER=`'.$dbNAME.'`@`%` PROCEDURE `IPER_MA_PRODUCT_GET_ID_BY_WPID` (IN `__WPID` BIGINT(20))  BEGIN
SELECT idPRODUCT
FROM '.$dbNAME.'.iper_MA_PRODUCT left join (SELECT fkPRODUCT,idRATEPLAN, Term,Price, META_WP_ID RATEPLAN_META_WP_ID
										FROM '.$dbNAME.'.iper_MA_RATEPLAN
										group by fkPRODUCT 
										having min(price)) AA on(idPRODUCT = fkPRODUCT)
                                        
where  iper_MA_PRODUCT.META_REQUEST_ID = IPER_MA_FUNC_LAST_RESPONSE_ID()  AND META_WP_ID = __WPID        ;
END$$

CREATE DEFINER=`'.$dbNAME.'`@`%` PROCEDURE `IPER_MA_PRODUCT_LIST` ()  BEGIN
SELECT * , AA.*
FROM '.$dbNAME.'.iper_MA_PRODUCT left join (SELECT fkPRODUCT,idRATEPLAN, Term,Price, META_WP_ID RATEPLAN_META_WP_ID
										FROM '.$dbNAME.'.iper_MA_RATEPLAN
										group by fkPRODUCT 
										having min(price)) AA on(idPRODUCT = fkPRODUCT)
                                        
where  iper_MA_PRODUCT.META_REQUEST_ID = IPER_MA_FUNC_LAST_RESPONSE_ID()
;

END$$

CREATE DEFINER=`'.$dbNAME.'`@`%` PROCEDURE `IPER_MA_PROMOTION_LIST` (`__idRATEPLAN` BIGINT(20))  BEGIN

	select *
    from  iper_MA_PROMOTION
    where fkRATEPLAN = __idRATEPLAN;


END$$

CREATE DEFINER=`'.$dbNAME.'`@`%` PROCEDURE `IPER_MA_RATEPLAN_GET` (IN `__idRATEPLAN` BIGINT(20))  BEGIN
SELECT * FROM '.$dbNAME.'.iper_MA_RATEPLAN where idRATEPLAN = __idRATEPLAN and META_REQUEST_ID = IPER_MA_FUNC_LAST_RESPONSE_ID();
END$$

CREATE DEFINER=`'.$dbNAME.'`@`%` PROCEDURE `IPER_MA_RATEPLAN_LIST` (IN `__fkPRODUCT` BIGINT(20))  BEGIN

SELECT * FROM '.$dbNAME.'.iper_MA_RATEPLAN where fkPRODUCT = __fkPRODUCT and META_REQUEST_ID = IPER_MA_FUNC_LAST_RESPONSE_ID();
END$$

CREATE DEFINER=`'.$dbNAME.'`@`%` PROCEDURE `IPER_MA_BUNDLE_SET` (`__fkBUNDLE_PRODUCT` BIGINT(20), `__fkPRODUCT` BIGINT(20), `__ID` LONGTEXT, `__Name` LONGTEXT, `__META_WP_ID` BIGINT(20), `__META_REQUEST_ID` BIGINT(20))  BEGIN


if(__fkBUNDLE_PRODUCT = 0) then
	select ifnull(META_WP_ID,0) ,ifnull(fkBUNDLE_PRODUCT,0) into __META_WP_ID ,__fkBUNDLE_PRODUCT from iper_MA_BUNDLE where ID = __ID and fkPRODUCT = __fkPRODUCT;
end if;


if(__fkBUNDLE_PRODUCT <> 0 ) then


	UPDATE `iper_MA_BUNDLE`
	SET
	
	`Name` = __Name,
	`META_WP_ID` = __META_WP_ID,
	`META_DATE_UPDATE` = now(),
	`META_REQUEST_ID` = __META_REQUEST_ID
	WHERE `fkBUNDLE_PRODUCT` = __fkBUNDLE_PRODUCT;



    call IPER_META_SHOWRESUL(1,__fkBUNDLE_PRODUCT);

else

	INSERT INTO `iper_MA_BUNDLE`
	(
    `fkPRODUCT`,
	`ID`,
	`Name`,
	`META_WP_ID`,
	`META_DATE_INSERT`,
	`META_DATE_UPDATE`,
	`META_REQUEST_ID`)
	VALUES
    (
        __fkPRODUCT,
        __ID,
        __Name,
        __META_WP_ID,
        now(),
        __META_REQUEST_ID);





	call IPER_META_SHOWRESUL(2,@@IDENTITY);


end if;
END$$

CREATE DEFINER=`'.$dbNAME.'`@`%` PROCEDURE `IPER_MA_CLEAN_WP_POST` ()  BEGIN
update wp_xygy_posts
set post_status = "trash"
where post_type  IN (
    "accessory"
    "product"
    "rateplan"
    "shipping"
    "upsell")
and ID NOT IN(
    select META_WP_ID 
from (
    select META_WP_ID
	from iper_MA_PRODUCT
	where META_REQUEST_ID = IPER_MA_FUNC_LAST_RESPONSE_ID()
	union
	select META_WP_ID
	from iper_MA_RATEPLAN
	where META_REQUEST_ID = IPER_MA_FUNC_LAST_RESPONSE_ID()
	union
	select META_WP_ID
	from iper_MA_UPSELL
	where META_REQUEST_ID = IPER_MA_FUNC_LAST_RESPONSE_ID()
	union
	select META_WP_ID
	from iper_MA_ACCESSORY_GROUP
	where META_REQUEST_ID = IPER_MA_FUNC_LAST_RESPONSE_ID()
	union
	select META_WP_ID
	from iper_MA_ACCESSORY
	where META_REQUEST_ID = IPER_MA_FUNC_LAST_RESPONSE_ID()
	union
	select META_WP_ID
	from iper_MA_SHIPPING
	where META_REQUEST_ID = IPER_MA_FUNC_LAST_RESPONSE_ID()
)AA 

);



update wp_xygy_posts
set post_status = "publish"
where post_type  IN (
    "accessory"
    "product"
    "rateplan"
    "shipping"
    "upsell")
and ID IN(
    select META_WP_ID 
from (
    select META_WP_ID
	from iper_MA_PRODUCT
	where META_REQUEST_ID = IPER_MA_FUNC_LAST_RESPONSE_ID()
	union
	select META_WP_ID
	from iper_MA_RATEPLAN
	where META_REQUEST_ID = IPER_MA_FUNC_LAST_RESPONSE_ID()
	union
	select META_WP_ID
	from iper_MA_UPSELL
	where META_REQUEST_ID = IPER_MA_FUNC_LAST_RESPONSE_ID()
	union
	select META_WP_ID
	from iper_MA_ACCESSORY_GROUP
	where META_REQUEST_ID = IPER_MA_FUNC_LAST_RESPONSE_ID()
	union
	select META_WP_ID
	from iper_MA_ACCESSORY
	where META_REQUEST_ID = IPER_MA_FUNC_LAST_RESPONSE_ID()
	union
	select META_WP_ID
	from iper_MA_SHIPPING
	where META_REQUEST_ID = IPER_MA_FUNC_LAST_RESPONSE_ID()
)AA 

) and post_status = "trash";

END$$

CREATE DEFINER=`'.$dbNAME.'`@`%` PROCEDURE `IPER_MA_PRODUCT_SET` (`__idPRODUCT` BIGINT(20), `__Brand` LONGTEXT, `__ProductName` LONGTEXT, `__ProductID` LONGTEXT, `__ProductCode` LONGTEXT, `__IsAvailable` LONGTEXT, `__META_WP_ID` BIGINT(20), `__META_REQUEST_ID` BIGINT(20))  BEGIN



if(__META_WP_ID = 0) then
	select ifnull(META_WP_ID,0) ,ifnull(idPRODUCT,0) into __META_WP_ID ,__idPRODUCT from iper_MA_PRODUCT where ProductID = __ProductID;
end if;


if(__META_WP_ID <> 0 ) then


	UPDATE `iper_MA_PRODUCT`
	SET
	`Brand` = __Brand,
	`ProductName` = __ProductName,
	`ProductCode` = __ProductCode,
	`IsAvailable` = __IsAvailable,
    `META_WP_ID` = __META_WP_ID,
    `META_DATE_UPDATE` = now(),
	`META_REQUEST_ID` = __META_REQUEST_ID
	WHERE `idPRODUCT` = __idPRODUCT;

	
    
    call IPER_META_SHOWRESUL(1,__idPRODUCT);

else

	INSERT INTO `iper_MA_PRODUCT`
	(
    `Brand`,
	`ProductName`,
	`ProductID`,
	`ProductCode`,
	`IsAvailable`,
	`META_WP_ID`,
	`META_DATE_INSERT`,
	`META_REQUEST_ID`)
	VALUES
    (
        __Brand,
        __ProductName,
        __ProductID,
        __ProductCode,
        __IsAvailable,
        __META_WP_ID,
        now(),
        __META_REQUEST_ID);



	call IPER_META_SHOWRESUL(2,@@IDENTITY);


end if;
end$$

CREATE DEFINER=`'.$dbNAME.'`@`%` PROCEDURE `IPER_MA_PROMOTION_SET` (`__idPROMOTION` BIGINT(20), `__fkRATEPLAN` BIGINT(20), `__PromotionID` LONGTEXT, `__Price` LONGTEXT, `__Name` LONGTEXT, `__META_WP_ID` BIGINT(20), `__META_REQUEST_ID` BIGINT(20))  BEGIN


if(__idPROMOTION = 0) then
	select ifnull(META_WP_ID,0) ,ifnull(idPROMOTION,0) into __META_WP_ID ,__idPROMOTION from iper_MA_PROMOTION where PromotionID = __PromotionID and fkRATEPLAN = __fkRATEPLAN;
end if;


if(__idPROMOTION <> 0 ) then


	UPDATE `iper_MA_PROMOTION`
	SET
	`Price` = __Price,
	`Name` = __Name,
	`META_WP_ID` = __META_WP_ID,
	`META_DATE_UPDATE` = now(),
	`META_REQUEST_ID` = __META_REQUEST_ID
	WHERE `idPROMOTION` = __idPROMOTION;




    call IPER_META_SHOWRESUL(1,__idPROMOTION);

else

	INSERT INTO `iper_MA_PROMOTION`
	(
    `fkRATEPLAN`,
	`PromotionID`,
	`Price`,
	`Name`,
	`META_WP_ID`,
	`META_DATE_INSERT`,
	`META_REQUEST_ID`)
	VALUES
    (
        __fkRATEPLAN,
        __PromotionID,
        __Price,
        __Name,
        __META_WP_ID,
        now(),
        __META_REQUEST_ID);




	call IPER_META_SHOWRESUL(2,@@IDENTITY);


end if;
END$$

CREATE DEFINER=`'.$dbNAME.'`@`%` PROCEDURE `IPER_MA_REQUEST_SET` (`__idREQUEST` BIGINT(20), `__URL_SEND` LONGTEXT, `__JSON_SEND` LONGTEXT, `__ResponseId` LONGTEXT, `__JSON_RESPONSE` LONGTEXT, `__META_USER` BIGINT(20))  BEGIN




if(__idREQUEST <> 0 ) then


	UPDATE `iper_MA_REQUEST`
	SET
		`ResponseId` = __ResponseId,
		`JSON_RESPONSE` = __JSON_RESPONSE,
		`META_DATE_RESPONSE` = now(),
		`META_USER` = __META_USER
	WHERE `idREQUEST` = __idREQUEST;

	
    
    call IPER_META_SHOWRESUL(1,__idREQUEST);

else

	INSERT INTO `iper_MA_REQUEST`
	(
    `URL_SEND`,
	`JSON_SEND`,
	`META_DATE_SEND`,
	`META_USER`)
	VALUES
    (__URL_SEND,
        __JSON_SEND,
        now(),
        __META_USER);


	call IPER_META_SHOWRESUL(1,@@IDENTITY);


end if;










END$$

CREATE DEFINER=`'.$dbNAME.'`@`%` PROCEDURE `IPER_MA_REQUEST_SET_LOG` (`__idREQUEST` BIGINT(20), `__META_LOG` LONGTEXT)  BEGIN

update iper_MA_REQUEST
set META_LOG = __META_LOG
where idREQUEST = __idREQUEST;


END$$

CREATE DEFINER=`'.$dbNAME.'`@`%` PROCEDURE `IPER_MA_SHIPPING_LIST` ()  BEGIN


SELECT * FROM '.$dbNAME.'.iper_MA_SHIPPING where META_REQUEST_ID = IPER_MA_FUNC_LAST_RESPONSE_ID();

END$$

CREATE DEFINER=`'.$dbNAME.'`@`%` PROCEDURE `IPER_MA_UPSELL_LIST` (`__fkRATEPLAN` BIGINT(20))  BEGIN

SELECT * FROM '.$dbNAME.'.iper_MA_UPSELL where fkRATEPLAN = __fkRATEPLAN and META_REQUEST_ID = IPER_MA_FUNC_LAST_RESPONSE_ID();


END$$

CREATE DEFINER=`'.$dbNAME.'`@`%` PROCEDURE `IPER_META_SHOWRESUL` (`__error` INT, `__identity` BIGINT(20))  BEGIN

declare __message longtext;


if(__error = 1 or __error = 2) then
	set __message = "ok";
else
	if (__error = -1) then
		set __message = "error cron request";
    else
		set __message = "error";
    end if;
	
end if ;

select 	__error status,__message msg,__identity identity;

END$$



CREATE DEFINER=`'.$dbNAME.'`@`%` PROCEDURE `IPER_MA_RATEPLAN_SET` (`__idRATEPLAN` BIGINT(20), `__fkPRODUCT` BIGINT(20), `__Term` LONGTEXT, `__RatePlanID` LONGTEXT, `__Price` LONGTEXT, `__OrderType` LONGTEXT, `__META_WP_ID` BIGINT(20), `__META_REQUEST_ID` BIGINT(20))  BEGIN


if(__META_WP_ID = 0) then
	select ifnull(META_WP_ID,0) ,ifnull(idRATEPLAN,0) into __META_WP_ID ,__idRATEPLAN from iper_MA_RATEPLAN where RatePlanID = __RatePlanID and fkPRODUCT = __fkPRODUCT;
end if;


if(__META_WP_ID <> 0 ) then


	UPDATE `iper_MA_RATEPLAN`
	SET
		`Term` = __Term,
		`Price` = __Price,
		`OrderType` = __OrderType,
		`META_WP_ID` = __META_WP_ID,
		`META_DATE_UPDATE` = now(),
		`META_REQUEST_ID` =__META_REQUEST_ID
	WHERE `idRATEPLAN` = __idRATEPLAN;


	
    
    call IPER_META_SHOWRESUL(1,__idRATEPLAN);

else

	INSERT INTO `iper_MA_RATEPLAN`
	(
    `fkPRODUCT`,
	`Term`,
	`RatePlanID`,
	`Price`,
	`OrderType`,
	`META_DATE_INSERT`,
	`META_REQUEST_ID`)
	VALUES
    (
        __fkPRODUCT,
        __Term,
        __RatePlanID,
        __Price,
        __OrderType,
        now(),
        __META_REQUEST_ID);




	call IPER_META_SHOWRESUL(2,@@IDENTITY);


end if;
END$$

CREATE DEFINER=`'.$dbNAME.'`@`%` PROCEDURE `IPER_MA_SHIPPING_SET` (`__idSHIPPING` BIGINT(20), `__Brand` LONGTEXT, `__ShippingMethod` LONGTEXT, `__ShippingID` LONGTEXT, `__ShippingCarrier` LONGTEXT, `__Price` LONGTEXT, `__META_WP_ID` BIGINT(20), `__META_REQUEST_ID` BIGINT(20))  BEGIN



if(__META_WP_ID = 0) then
	select ifnull(META_WP_ID,0) ,ifnull(idSHIPPING,0) into __META_WP_ID ,__idSHIPPING from iper_MA_SHIPPING where ShippingID = __ShippingID;
end if;


if(__META_WP_ID <> 0 ) then


	UPDATE `iper_MA_SHIPPING`
	SET
	`ShippingMethod` = __ShippingMethod,
	`ShippingCarrier` = __ShippingCarrier,
	`Price` = __Price,
	`META_WP_ID` = __META_WP_ID,
	`META_DATE_UPDATE` = now(),
	`META_REQUEST_ID` = __META_REQUEST_ID
	WHERE `idSHIPPING` = __idSHIPPING;


	
    
    call IPER_META_SHOWRESUL(1,__idSHIPPING);

else

	INSERT INTO `iper_MA_SHIPPING`
	(
    `Brand`,
	`ShippingMethod`,
	`ShippingID`,
	`ShippingCarrier`,
	`Price`,
	`META_WP_ID`,
	`META_DATE_INSERT`,
	`META_REQUEST_ID`)
	VALUES
    (
        __Brand,
        __ShippingMethod,
        __ShippingID,
        __ShippingCarrier,
        __Price,
        __META_WP_ID,
        now(),
        __META_REQUEST_ID);




	call IPER_META_SHOWRESUL(2,@@IDENTITY);


end if;
end$$

CREATE DEFINER=`'.$dbNAME.'`@`%` PROCEDURE `IPER_MA_UPSELL_SET` (`__idUPSEL` BIGINT(20), `__fkRATEPLAN` BIGINT(20), `__ProductName` LONGTEXT, `__ProductID` LONGTEXT, `__Price` LONGTEXT, `__ShowBeforeAccessories` LONGTEXT, `__META_WP_ID` BIGINT(20), `__META_REQUEST_ID` BIGINT(20))  BEGIN


if(__META_WP_ID = 0) then
	select ifnull(META_WP_ID,0) ,ifnull(idUPSEL,0) into __META_WP_ID ,__idUPSEL from iper_MA_UPSELL where ProductID = __ProductID and fkRATEPLAN = __fkRATEPLAN;
end if;


if(__META_WP_ID <> 0 ) then


	UPDATE `'.$dbNAME.'`.`iper_MA_UPSELL`
	SET
	`ProductName` =__ProductName,
	`Price` = __Price,
	`ShowBeforeAccessories` = __ShowBeforeAccessories,
	`META_WP_ID` = __META_WP_ID,
	`META_DATE_UPDATE` = now(),
	`META_REQUEST_ID` = __META_REQUEST_ID
	WHERE `idUPSEL` = __idUPSEL;

    
    call IPER_META_SHOWRESUL(1,__idUPSEL);

else

	INSERT INTO `'.$dbNAME.'`.`iper_MA_UPSELL`
	(
    `fkRATEPLAN`,
	`ProductName`,
	`ProductID`,
	`Price`,
	`ShowBeforeAccessories`,
	`META_WP_ID`,
	`META_DATE_INSERT`,
	
	`META_REQUEST_ID`)
	VALUES
    (
        __fkRATEPLAN,
        __ProductName,
        __ProductID,
        __Price,
        __ShowBeforeAccessories,
        __META_WP_ID,
        now(),

        __META_REQUEST_ID);





	call IPER_META_SHOWRESUL(2,@@IDENTITY);


end if;
END$$

CREATE DEFINER=`'.$dbNAME.'`@`%` PROCEDURE `IPER_MA_UPSELL_GET`(IN __idUPSEL BIGINT(20))
BEGIN

SELECT * FROM iper_MA_UPSELL WHERE idUPSEL = __idUPSEL;

END$$

--
-- Funzioni
--
CREATE DEFINER=`'.$dbNAME.'`@`%` FUNCTION `IPER_MA_FUNC_LAST_RESPONSE_ID` () RETURNS BIGINT(20) BEGIN

declare __r bigint(20);

SELECT max(idREQUEST) into __r  FROM '.$dbNAME.'.iper_MA_REQUEST where ResponseId <> "" and not ResponseId is null;

RETURN __r;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Struttura della tabella `iper_MA_ACCESSORY`
--

CREATE TABLE IF NOT EXISTS `iper_MA_ACCESSORY` (
`idACCESSORY` bigint(20) NOT NULL AUTO_INCREMENT,
  `Quantity` longtext,
  `Price` longtext,
  `IsAvailable` longtext,
  `AccessoryName` longtext,
  `AccessoryID` longtext,
  `AccessoryCode` longtext,
  `META_WP_ID` bigint(20) DEFAULT NULL,
  `META_DATE_INSERT` datetime DEFAULT NULL,
  `META_DATE_UPDATE` datetime DEFAULT NULL,
  `META_REQUEST_ID` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`idACCESSORY`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `iper_MA_ACCESSORY_GROUP`
--

CREATE TABLE IF NOT EXISTS `iper_MA_ACCESSORY_GROUP` (
`idACCESSORY_GROUP` bigint(20) NOT NULL AUTO_INCREMENT,
  `fkRATEPLAN` bigint(20) NOT NULL,
  `LimitRestriction` longtext,
  `IncludedWithOrder` longtext,
  `Description` longtext,
  `AccessoryGroupID` longtext,
  `META_WP_ID` bigint(20) DEFAULT NULL,
  `META_DATE_INSERT` datetime DEFAULT NULL,
  `META_DATE_UPDATE` datetime DEFAULT NULL,
  `META_REQUEST_ID` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`idACCESSORY_GROUP`),
  KEY `fk_iper_MA_ACCESSORY_GROUP_iper_MA_RATEPLAN1_idx` (`fkRATEPLAN`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `iper_MA_ACCESSORY_GROUP_ACCESSORY`
--

CREATE TABLE IF NOT EXISTS `iper_MA_ACCESSORY_GROUP_ACCESSORY` (
`fkACCESSORY_GROUP` bigint(20) NOT NULL,
  `fkACCESSORY` bigint(20) NOT NULL,
  `Price` longtext,
  `META_DATE_INSERT` datetime DEFAULT NULL,
  `META_DATE_UPDATE` datetime DEFAULT NULL,
  `META_REQUEST_ID` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`fkACCESSORY_GROUP`,`fkACCESSORY`),
  KEY `fk_iper_MA_ACCESSORY_has_iper_MA_ACCESSORY_GROUP_iper_MA_AC_idx` (`fkACCESSORY_GROUP`),
  KEY `fk_iper_MA_ACCESSORY_has_iper_MA_ACCESSORY_GROUP_iper_MA_AC_idx1` (`fkACCESSORY`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `iper_MA_BUNDLE`
--

CREATE TABLE IF NOT EXISTS `iper_MA_BUNDLE` (
`fkBUNDLE_PRODUCT` bigint(20) NOT NULL AUTO_INCREMENT,
  `fkPRODUCT` bigint(20) NOT NULL,
  `ID` longtext,
  `Name` longtext,
  `META_WP_ID` bigint(20) DEFAULT NULL,
  `META_DATE_INSERT` datetime DEFAULT NULL,
  `META_DATE_UPDATE` datetime DEFAULT NULL,
  `META_REQUEST_ID` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`fkBUNDLE_PRODUCT`),
  KEY `fk_iper_MA_BUNDLE_iper_MA_PRODUCT2_idx` (`fkPRODUCT`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `iper_MA_DISCOUNT`
--

CREATE TABLE IF NOT EXISTS `iper_MA_DISCOUNT` (
`idDISCOUNT` bigint(20) NOT NULL,
  `Name` longtext,
  `DiscountType` longtext,
  `DiscountID` longtext,
  `DiscountCode` longtext,
  `DiscountAmount` longtext,
  `META_WP_ID` bigint(20) DEFAULT NULL,
  `META_DATE_INSERT` datetime DEFAULT NULL,
  `META_DATE_UPDATE` datetime DEFAULT NULL,
  `META_REQUEST_ID` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`idDISCOUNT`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `iper_MA_DISCOUNT_PRODUCT`
--

CREATE TABLE IF NOT EXISTS `iper_MA_DISCOUNT_PRODUCT` (
`idDISCOUNT_PRODUCT` bigint(20) NOT NULL,
  `fkDISCOUNT` bigint(20) NOT NULL,
  `fkPRODUCT` bigint(20) NOT NULL,
  `META_DATE_INSERT` datetime DEFAULT NULL,
  `META_DATE_UPDATE` datetime DEFAULT NULL,
  `META_REQUEST_ID` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`idDISCOUNT_PRODUCT`),
  KEY `fk_iper_MA_DISCOUNT_PRODUCT_iper_MA_DISCOUNT1_idx` (`fkDISCOUNT`),
  KEY `fk_iper_MA_DISCOUNT_PRODUCT_iper_MA_PRODUCT1_idx` (`fkPRODUCT`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `iper_MA_DISCOUNT_SHIPPING`
--

CREATE TABLE IF NOT EXISTS `iper_MA_DISCOUNT_SHIPPING` (
`idDISCOUNT_SHIPPING` bigint(20) NOT NULL,
  `fkDISCOUNT` bigint(20) NOT NULL,
  `fkSHIPPING` bigint(20) NOT NULL,
  `META_DATE_INSERT` datetime DEFAULT NULL,
  `META_DATE_UPDATE` datetime DEFAULT NULL,
  `META_REQUEST_ID` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`idDISCOUNT_SHIPPING`),
  KEY `fk_iper_MA_DISCOUNT_SHIPPING_iper_MA_DISCOUNT1_idx` (`fkDISCOUNT`),
  KEY `fk_iper_MA_DISCOUNT_SHIPPING_iper_MA_SHIPPING1_idx` (`fkSHIPPING`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `iper_MA_PRODUCT`
--

CREATE TABLE IF NOT EXISTS `iper_MA_PRODUCT` (
`idPRODUCT` bigint(20) NOT NULL AUTO_INCREMENT,
  `Brand` longtext,
  `ProductName` longtext,
  `ProductID` longtext,
  `ProductCode` longtext,
  `IsAvailable` longtext,
  `BaseMonthlyPrice` longtext,
  `META_WP_ID` bigint(20) DEFAULT NULL,
  `META_DATE_INSERT` datetime DEFAULT NULL,
  `META_DATE_UPDATE` datetime DEFAULT NULL,
  `META_REQUEST_ID` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`idPRODUCT`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `iper_MA_PROMOTION`
--

CREATE TABLE IF NOT EXISTS `iper_MA_PROMOTION` (
`idPROMOTION` bigint(20) NOT NULL AUTO_INCREMENT,
  `fkRATEPLAN` bigint(20) NOT NULL,
  `PromotionID` longtext,
  `Price` longtext,
  `Name` longtext,
  `META_WP_ID` bigint(20) DEFAULT NULL,
  `META_DATE_INSERT` datetime DEFAULT NULL,
  `META_DATE_UPDATE` datetime DEFAULT NULL,
  `META_REQUEST_ID` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`idPROMOTION`),
  KEY `fk_iper_MA_PROMOTION_iper_MA_RATEPLAN1_idx` (`fkRATEPLAN`)
) ENGINE=InnoDB AUTO_INCREMENT=92 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `iper_MA_RATEPLAN`
--

CREATE TABLE IF NOT EXISTS `iper_MA_RATEPLAN` (
`idRATEPLAN` bigint(20) NOT NULL AUTO_INCREMENT,
  `fkPRODUCT` bigint(20) NOT NULL,
  `Term` longtext,
  `RatePlanID` longtext,
  `Price` longtext,
  `OrderType` longtext,
  `META_WP_ID` bigint(20) DEFAULT NULL,
  `META_DATE_INSERT` datetime DEFAULT NULL,
  `META_DATE_UPDATE` datetime DEFAULT NULL,
  `META_REQUEST_ID` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`idRATEPLAN`),
  KEY `fk_iper_MA_RATEPLAN_iper_MA_PRODUCT_idx` (`fkPRODUCT`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `iper_MA_REQUEST`
--

CREATE TABLE IF NOT EXISTS `iper_MA_REQUEST` (
`idREQUEST` int(11) NOT NULL AUTO_INCREMENT,
  `URL_SEND` longtext,
  `JSON_SEND` longtext,
  `ResponseId` longtext,
  `JSON_RESPONSE` longtext,
  `META_LOG` longtext,
  `META_DATE_SEND` datetime DEFAULT NULL,
  `META_DATE_RESPONSE` datetime DEFAULT NULL,
  `META_USER` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`idREQUEST`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `iper_MA_SHIPPING`
--

CREATE TABLE IF NOT EXISTS `iper_MA_SHIPPING` (
`idSHIPPING` bigint(20) NOT NULL AUTO_INCREMENT,
  `Brand` longtext,
  `ShippingMethod` longtext,
  `ShippingID` longtext,
  `ShippingCarrier` longtext,
  `Price` longtext,
  `META_WP_ID` bigint(20) DEFAULT NULL,
  `META_DATE_INSERT` datetime DEFAULT NULL,
  `META_DATE_UPDATE` datetime DEFAULT NULL,
  `META_REQUEST_ID` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`idSHIPPING`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `iper_MA_UPSELL`
--

CREATE TABLE IF NOT EXISTS `iper_MA_UPSELL` (
`idUPSEL` bigint(20) NOT NULL AUTO_INCREMENT,
  `fkRATEPLAN` bigint(20) NOT NULL,
  `ProductName` longtext,
  `ProductID` longtext,
  `Price` longtext,
  `ShowBeforeAccessories` longtext,
  `META_WP_ID` bigint(20) DEFAULT NULL,
  `META_DATE_INSERT` datetime DEFAULT NULL,
  `META_DATE_UPDATE` datetime DEFAULT NULL,
  `META_REQUEST_ID` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`idUPSEL`),
  KEY `fk_iper_MA_UPSELL_iper_MA_RATEPLAN1_idx` (`fkRATEPLAN`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;



--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `iper_MA_ACCESSORY_GROUP`
--
ALTER TABLE `iper_MA_ACCESSORY_GROUP`
  ADD CONSTRAINT `fk_iper_MA_ACCESSORY_GROUP_iper_MA_RATEPLAN1` FOREIGN KEY (`fkRATEPLAN`) REFERENCES `iper_MA_RATEPLAN` (`idRATEPLAN`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limiti per la tabella `iper_MA_ACCESSORY_GROUP_ACCESSORY`
--
ALTER TABLE `iper_MA_ACCESSORY_GROUP_ACCESSORY`
  ADD CONSTRAINT `fk_iper_MA_ACCESSORY_has_iper_MA_ACCESSORY_GROUP_iper_MA_ACCE1` FOREIGN KEY (`fkACCESSORY`) REFERENCES `iper_MA_ACCESSORY` (`idACCESSORY`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_iper_MA_ACCESSORY_has_iper_MA_ACCESSORY_GROUP_iper_MA_ACCE2` FOREIGN KEY (`fkACCESSORY_GROUP`) REFERENCES `iper_MA_ACCESSORY_GROUP` (`idACCESSORY_GROUP`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limiti per la tabella `iper_MA_BUNDLE`
--
ALTER TABLE `iper_MA_BUNDLE`
  ADD CONSTRAINT `fk_iper_MA_BUNDLE_iper_MA_PRODUCT2` FOREIGN KEY (`fkPRODUCT`) REFERENCES `iper_MA_PRODUCT` (`idPRODUCT`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limiti per la tabella `iper_MA_DISCOUNT_PRODUCT`
--
ALTER TABLE `iper_MA_DISCOUNT_PRODUCT`
  ADD CONSTRAINT `fk_iper_MA_DISCOUNT_PRODUCT_iper_MA_DISCOUNT1` FOREIGN KEY (`fkDISCOUNT`) REFERENCES `iper_MA_DISCOUNT` (`idDISCOUNT`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_iper_MA_DISCOUNT_PRODUCT_iper_MA_PRODUCT1` FOREIGN KEY (`fkPRODUCT`) REFERENCES `iper_MA_PRODUCT` (`idPRODUCT`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limiti per la tabella `iper_MA_DISCOUNT_SHIPPING`
--
ALTER TABLE `iper_MA_DISCOUNT_SHIPPING`
  ADD CONSTRAINT `fk_iper_MA_DISCOUNT_SHIPPING_iper_MA_DISCOUNT1` FOREIGN KEY (`fkDISCOUNT`) REFERENCES `iper_MA_DISCOUNT` (`idDISCOUNT`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_iper_MA_DISCOUNT_SHIPPING_iper_MA_SHIPPING1` FOREIGN KEY (`fkSHIPPING`) REFERENCES `iper_MA_SHIPPING` (`idSHIPPING`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limiti per la tabella `iper_MA_PROMOTION`
--
ALTER TABLE `iper_MA_PROMOTION`
  ADD CONSTRAINT `fk_iper_MA_PROMOTION_iper_MA_RATEPLAN1` FOREIGN KEY (`fkRATEPLAN`) REFERENCES `iper_MA_RATEPLAN` (`idRATEPLAN`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limiti per la tabella `iper_MA_RATEPLAN`
--
ALTER TABLE `iper_MA_RATEPLAN`
  ADD CONSTRAINT `fk_iper_MA_RATEPLAN_iper_MA_PRODUCT` FOREIGN KEY (`fkPRODUCT`) REFERENCES `iper_MA_PRODUCT` (`idPRODUCT`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limiti per la tabella `iper_MA_UPSELL`
--
ALTER TABLE `iper_MA_UPSELL`
  ADD CONSTRAINT `fk_iper_MA_UPSELL_iper_MA_RATEPLAN1` FOREIGN KEY (`fkRATEPLAN`) REFERENCES `iper_MA_RATEPLAN` (`idRATEPLAN`) ON DELETE NO ACTION ON UPDATE NO ACTION;

';


    $wpdb->query($sql);
?>