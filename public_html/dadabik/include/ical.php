<?php
$timezone_needed = 0;

$res_records_without_limit = execute_db($select_without_order_limit, $conn);
                                
$calendar_events_ar = build_calendar_events_ar($res_records_without_limit, $fields_labels_ar, $table_infos_ar, $table_name);

if (count($calendar_events_ar) > $max_records_export_ical){
    die('Max number of events is set to '.$max_records_export_ical.', please set the configuration parameter $max_records_export_ical to increase it' );
}

// we may have multiple ob opened, we start one in common_start, another could be started by php.ini by default
// note: ob_clean() would generate an infine loop
while (ob_get_level()) {
    ob_end_clean();
}

require_once 'vendor/autoload.php';

$site_url_calendar = "http".(isset($_SERVER['HTTPS']) ? 's' : ''). '://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'/';

$cnt_events = 0;
foreach ($calendar_events_ar as $calendar_event){

    $event_uid = $site_url_calendar.$table_name.'/'.$calendar_event['id'];
    $UID = new Eluceo\iCal\Domain\ValueObject\UniqueIdentifier($event_uid);

    // Create Event
    $ical_events[$cnt_events] = new Eluceo\iCal\Domain\Entity\Event($UID);
        
    $ical_events[$cnt_events]->setSummary($calendar_event['title']);

    if (isset($calendar_event['description'])){
        $ical_events[$cnt_events]->setDescription($calendar_event['description']);
    }

    $uri = new Eluceo\iCal\Domain\ValueObject\Uri($site_url_calendar.'index.php?tablename='.urlencode($table_name).'&function=details&where_field='.urlencode($unique_field_name).'&where_value='.urlencode($calendar_event['id']));
    $ical_events[$cnt_events]->setUrl($uri);

    if (isset($calendar_event['location'])){
        $location = new Eluceo\iCal\Domain\ValueObject\Location($calendar_event['location']);
        $ical_events[$cnt_events]->setLocation($location);
    }
    
    if ($ical_timestamp_current_time === 1){
        $date_time_to_use = date("Y-m-d H:i:s");
    }
    else{
        $date_time_to_use = $calendar_event['lastupdate'];
    }
    $date_time = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $date_time_to_use);
    $timestamp = new Eluceo\iCal\Domain\ValueObject\Timestamp($date_time);
    $ical_events[$cnt_events]->touch($timestamp);

    /* we have three cases
    1) single day, all-day event, date only
    2) multiple dates event, date only
    3) start and end datetime (same day or multiple days)
    */

    $field_type_start = 'date_time';
    $date_format_start = 'Y-m-d H:i:s';
    if (strlen($calendar_event['start']) === 10){ // date only
        $field_type_start = 'date';
        $date_format_start = 'Y-m-d';
    }

    if (isset($calendar_event['end'])){ // start and end, could be same day or multiple days 

        $field_type_end = 'date_time';
        if (strlen($calendar_event['end']) === 10){ // date only
            $field_type_end = 'date';
        }

        if ($field_type_start !== $field_type_end){
            die('iCal error: wrong date format start vs end');
        }

        if ($field_type_start === 'date'){ // multiple days, date only

            $start_date = new Eluceo\iCal\Domain\ValueObject\Date(\DateTimeImmutable::createFromFormat('Y-m-d', $calendar_event['start']), false);
            $end_date = new Eluceo\iCal\Domain\ValueObject\Date(\DateTimeImmutable::createFromFormat('Y-m-d', $calendar_event['end']), false);
            
            $occurrence = new Eluceo\iCal\Domain\ValueObject\MultiDay($start_date, $end_date);

        }
        else{ //  datetime, TimeSpan

            $timezone_needed = 1;

            $start_date = new Eluceo\iCal\Domain\ValueObject\DateTime(\DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $calendar_event['start']), true);

            if (!isset($min_date) || $calendar_event['start'] < $min_date ){
                $min_date = $calendar_event['start'];
            }

            $end_date = new Eluceo\iCal\Domain\ValueObject\DateTime(\DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $calendar_event['end']), true);

            if (!isset($max_date) || $calendar_event['end'] > $max_date ){
                $max_date = $calendar_event['end'];
            }
            
            $occurrence = new Eluceo\iCal\Domain\ValueObject\TimeSpan($start_date, $end_date);

        }

        $ical_events[$cnt_events]->setOccurrence($occurrence);
    }
    else{ // single day
        $ical_events[$cnt_events]->setOccurrence(
                new Eluceo\iCal\Domain\ValueObject\SingleDay(
                    new Eluceo\iCal\Domain\ValueObject\Date(
                        \DateTimeImmutable::createFromFormat($date_format_start, $calendar_event['start'])
                    )
                )
        );
    }
    $cnt_events++;
}

// Create Calendar
$calendar = new Eluceo\iCal\Domain\Entity\Calendar($ical_events);

if ($timezone_needed === 1){
    // $timezone is a config parameter
    $php_date_time_zone = new DateTimeZone($timezone);
    $ical_time_zone = Eluceo\iCal\Domain\Entity\TimeZone::createFromPhpDateTimeZone(
        $php_date_time_zone,
        new DateTimeImmutable($min_date, $php_date_time_zone),
        new DateTimeImmutable($max_date, $php_date_time_zone)
    );

    $calendar->addTimeZone($ical_time_zone);
}

// Create an iCalendar presentation component
$component_factory = new Eluceo\iCal\Presentation\Factory\CalendarFactory();
$calendar_component = $component_factory->createCalendar($calendar);

// Set headers
header('Content-Type: text/calendar; charset=utf-8');
header('Content-Disposition: attachment; filename="'.$table_name.'.ics"');

// Print output
echo $calendar_component;

exit;