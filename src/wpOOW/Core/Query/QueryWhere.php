TODO: 

QueryWhere("_field", "value", QueryWhereOps::Equals)


foreach ($tnc_events->Query()->Select()->Where([QueryWhere("_field", "value", QueryWhereOps::Equals)])->First()->Fetch() as $row) {
              if ($row["_tnc_event_is_active"] == "on") {
                  $eventSummary = sprintf("%s. %s", $row["_tnc_event_name"], date("d M Y, h:i a", strtotime($row["_tnc_event_date_time_start"]) ));
                  break;
              }
          }   
    }