jQuery(document).ready(function () {
  const apiCall = function (args) {
  jQuery.ajax({
    url: "https://app.directshifts.com/jobs/p/list.json",
    async: true,
    data: args,
    crossDomain: true,
    success: function (result) {
      let root = document.getElementById("root");
      let html = "";
      if(result.jobs != ''){
      for (let i = 0; i < result.jobs.length; i++) {
        let specialty_names_str = result.jobs[i].specialty_names;
        let specialty_names_arr = specialty_names_str.split(",");
        let specialty_names_html = '';
          for(let k = 0; k < specialty_names_arr.length; k++){
            if( k <= 2){
              specialty_names_html += `<span class="jl-sn-inr-items">${specialty_names_arr[k]}</span>`;
            }
          }
let specialty_names = '';
          for(let k = 0; k < specialty_names_arr.length; k++){
            if( k <= 2){
              specialty_names += `{specialty_names_arr[k]}`;
            }
          }
         let practice_type_obj = result.jobs[i].practice_type;
         let practice_type_html = '';
         let pt = 0;
         for (const [key, value] of Object.entries(practice_type_obj)) {
          if(pt <= 2){
           practice_type_html += `<span class="jl-pt-inr-items">${value}</span>`;
            }
            pt++;
          }
          let practice_type_strr = `<span class="jl-pt-inr-items">${result.jobs[i].practice_type}</span>`;
    html += `<tr class="my-code">
  <td>
  <div class="jl-title-wrap">
  <div class="jl-title"><a target="_blank" class="data-link" href="${result.jobs[i].link}">${result.jobs[i].title}</a><div class="jl-main-head"><div class="jl-location"><img src="https://uploads-ssl.webflow.com/5abebcf1b3e0cb4bb9718bba/63c1428ec77fbc6cb91ce1c7_location-icon.png"/><span>${result.jobs[i].city},</span> <span>${result.jobs[i].state_code}</span></div></div></div><div class="apply-now-wrap"><a target="_blank" class="btn-applynow" href="${result.jobs[i].link}">Apply Now</a></div></div>
  <div class="jl-types">
  <a target="_blank" class="data-link" href="${result.jobs[i].link}"
  <div class="jl-sn-wrapper">
  <h6 class="jl-sn-heading">Specialty</h6>
  <div class="jl-sn">${specialty_names_html}</div>
  </div>
  <div class="jl-pt-wrapper">
  <h6 class="jl-pt-heading">Practice Type</h6>
  <div class="jl-pt">${typeof result.jobs[i].practice_type === "string" ? practice_type_strr : practice_type_html}</div>
  </div></a></div></td></tr>`;
  
}
  let pagination_html = '';
    if(result.total_pages > 1){
    if(result.current_page == 1){
    pagination_html = `<nav class="pagination">
    <span data-value="${result.current_page}" class="current">${result.current_page}</span>
    <span data-value="${result.next_page}" class="pgn-func">${result.next_page}</span>
    <span data-value="${result.current_page + 1}" rel="next" class="pgn-func next">Next</span>
    <span data-value="${result.total_pages}" class="pgn-func last last-1">Last</span>
    </nav>`;}else if(result.total_pages == result.current_page){
    pagination_html = `<nav class="pagination">
    <span data-value="1" class="pgn-func first">First</span>
    <span data-value="${result.current_page - 1}" class="pgn-func prev">Prev</span>
    <span data-value="${result.prev_page}" class="pgn-func">${result.prev_page}</span>
    <span class="current">${result.current_page}</span>
    </nav>`;}else{
    pagination_html = `<nav class="pagination">
    <span data-value="1" class="pgn-func first">First</span>
    <span data-value="${result.current_page - 1}" class="pgn-func prev">Prev</span>
    <span data-value="${result.prev_page}" class="pgn-func">${result.prev_page}</span>
    <span class="current">${result.current_page}</span>
    <span data-value="${result.next_page}" class="pgn-func">${result.next_page}</span>
    <span data-value="${result.current_page + 1}" rel="next" class="pgn-func next">Next</span>
    <span data-value="${result.total_pages}" class="pgn-func last last-1">Last</span>
    </nav>`;}}
    jQuery('.api-pagination').html(pagination_html);
      } else{
       html = "No Jobs Found";
       jQuery('.api-pagination').html('');
    }
root.innerHTML = html;
if (!document.getElementById('lists_state')) {
    let states_html = "";
      for(let j = 0; j < result.states.length; j++){
        states_html+= `<label>
        <input type="radio" class="api-state" name="state" value="${result.states[j].code}">
        <span>${result.states[j].code}</span>
        ${result.states[j].name}
      </label>`;
    }
    jQuery('.apiState').after(`<datalist id="lists_state">${states_html}</datalist>`);
}
if (!document.getElementById('lists_city')) {
  let uniqueCitylist = ['Loganville','Hudson','Chico','Remote','Any', 'Martinez', 'Modesto', 'Chicago','Brooklyn','Saratoga springs','Yonkers', 'Muskegon', 'Wilmington', 'Staten island', 'Austin'];
  let city_html = "";
  for (let j = 0; j < uniqueCitylist.length; j++) {
    const cityList = uniqueCitylist[j];
    city_html += `<label>
      <input type="radio" class="api-city" name="city_radio" value="${cityList}">
      ${cityList}
     </label>`;
     }
jQuery('.apiCity').after(`<datalist id="lists_city">${city_html}</datalist>`);
}
if (!document.getElementById('lists_speciality')) {
  let specialties_html = "";
  for (let j = 0; j < result.specialties.length; j++) {
    const specialty = result.specialties[j];
    specialties_html += `<label>
    <input type="radio" class="api-speciality" id="specialty_radio_${j}" name="specialty_radio" value="${specialty}">
     ${specialty}
    </label>`;
  }
  jQuery('.listSpeciality').after(`<datalist id="lists_speciality">${specialties_html}</datalist>`);
}

if (!document.getElementById('lists_practice_type')) {
let uniquePracticeTypes = ['Emergency Department','Inpatient','Outpatient','Telemedicine','Urgent Care','Correctional','Home Care','Clinic/Office-Based Practice','Addiction Center/Rehab'];
 let practice_type_html = "";
  for (let j = 0; j < uniquePracticeTypes.length; j++) {
   const practiceType = uniquePracticeTypes[j];
    practice_type_html += `<label>
     <input type="radio" class="api-practice-type" name="practice_types" value="${practiceType}">
       ${practiceType}
    </label>`;
   }
  jQuery('.listPractice').after(`<datalist id="lists_practice_type">${practice_type_html}</datalist>`);
}
if (!document.getElementById('lists_category')) {
  let uniqueCategories = ['Contract','Per-Diem','Permanent'];
  let categories_html = "";
  for (let j = 0; j < uniqueCategories.length; j++) {
   const category = uniqueCategories[j]; 
   categories_html += `<label>
    <input type="radio" class="api-category" name="categories" value="${category}">
       ${category}
    </label>`;
    }
  jQuery('.listCategory').after(`<datalist id="lists_category">${categories_html}</datalist>`);
}
if (!document.getElementById('lists_occupation')) {
    let uniqueOccupations = ['Physician (MD/DO)','Physician Assistant (PA)','Advanced Practice Registered Nurse (APRN, NP, CNS, Midwife, etc.)','Certified Registered Nurse Anesthesist (CRNA)','NurseMidwife','Nurse Specialist/CNS','Registered Nurse (RN)','Licensed Practical Nurse/Licensed Vocational Nurse (LVN, LPN)','Certified Nursing Assistant (CNA)','Patient Care Technician (PCT)','Respiratory, Rehabilitative & Developmental Therapists (OT, PT, etc.)','Technologist (Sonography, Radiology, etc.)','Technician (Radiologic, Cardiovascular, Histo, etc.)','Medical Assistants (CMA, RMA)','Dental Services (DDS, Technician, Hygienist, etc.)','Behavioral Health & Social Work Services (Counselors, Social Workers, etc.)','Dietitians and Nutritionists','Pharmacist','Healthcare Administration'];
  let occupations_html = "";
  for (let j = 0; j < uniqueOccupations.length; j++) {
  const occupation = uniqueOccupations[j];
    occupations_html += `<label>
      <input type="radio" class="api-occupation" name="occupations" value="${occupation}">
         ${occupation}
     </label>`;
   }
  jQuery('.apiOccupation').after(`<datalist id="lists_occupation">${occupations_html}</datalist>`);
}
},
});
};
const queryString = window.location.search;
 if(queryString){
  const urlParams = new URLSearchParams(queryString);
  const query_params = Object.fromEntries(urlParams);
  apiCall(query_params); 
}else{
  apiCall(); 
}
jQuery(document).on("click","datalist input",function(){let e=jQuery('input[type="radio"]:checked').length;jQuery("#checked-count").text("Applied ("+e+")");let a=jQuery(".api-state:checked").val(),t=jQuery(".api-city:checked").val(),c=jQuery(".api-occupation:checked").val(),s=jQuery(".api-speciality:checked").val(),i=jQuery(".api-category:checked").val(),p=jQuery(".api-practice-type:checked").val(),r=new URL("https://www.directshifts.com/job-listing-copy-aug-24");a&&r.searchParams.set("state",a),t&&r.searchParams.set("city",t),c&&r.searchParams.set("occupation",c),s&&r.searchParams.set("specialty",s),i&&r.searchParams.set("category",i),p&&r.searchParams.set("practice_type",p),window.history.pushState(null,"",r.toString()),apiCall({state:a,city:t,occupation:c,specialty:s,category:i,practice_type:p})});
jQuery("#clear-all-button").on("click",function(){jQuery('input[type="radio"]').prop("checked",!1);let e=new URL("https://www.directshifts.com/job-listing-copy-aug-24");e.searchParams.delete("state"),e.searchParams.delete("city"),e.searchParams.delete("occupation"),e.searchParams.delete("specialty"),e.searchParams.delete("category"),e.searchParams.delete("practice_type"),window.history.pushState(null,"",e.toString()),apiCall({state:null,city:null,occupation:null,specialty:null,category:null,practice_type:null}),jQuery("#checked-count").text("Applied (0)")});
jQuery(document).on("click","span.pgn-func",function(){let a=jQuery(".api-state").val(),i=jQuery(".api-city").val(),t=jQuery(".api-occupation").val(),p=jQuery(".api-speciality").val(),c=jQuery(".api-category").val(),l=jQuery(".api-practice-type").val(),e=jQuery(this).attr("data-value");apiCall({state:a,city:i,occupation:t,specialty:p,category:c,practice_type:l,page:e})});
});
