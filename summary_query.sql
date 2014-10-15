select sum(forcib_or_nonfor), year, instnm 
from summary 
where
  on_or_off_campus = 'Total on or off campus' 
group by instnm, year
order by instnm, year;