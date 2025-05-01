<?php $faculty_id = $_SESSION['login_id'] ?>
<?php 
function ordinal_suffix($num){
    $num = $num % 100; // protect against large numbers
    if($num < 11 || $num > 13){
         switch($num % 10){
            case 1: return $num.'st';
            case 2: return $num.'nd';
            case 3: return $num.'rd';
        }
    }
    return $num.'th';
}
?>
<style>
  .text-success, .border-success {
    color: #417029 !important;
    border-color: #417029 !important;
  }
  .bg-primary {
    background-color: #183f74 !important;
  }
  .text-primary {
    color: #183f74 !important;
  }
  .list-group-item.active {
    background-color: #183f74 !important;
    border-color: #183f74 !important;
  }
  .list-group-item.active:hover {
    color: white !important;
  }
  .list-group-item:hover {
    color: #183f74 !important;
    font-weight: 600 !important;
  }
  .callout.callout-info {
    border-left-color: #183f74 !important;
  }
  .bg-gradient-secondary {
    background: #6c757d linear-gradient(180deg, #828a91, #6c757d) repeat-x !important;
    color: #fff;
  }
  .btn-print {
    background-color: #417029 !important;
    border-color: #417029 !important;
  }
  .btn-print:hover {
    background-color: #345821 !important;
    border-color: #345821 !important;
  }
</style>

<div class="container-fluid px-4">
  <div class="row">
    <div class="col-md-12 mb-3">
      <div class="d-flex justify-content-end">
        <button class="btn btn-sm btn-print text-white" style="display:none" id="print-btn"><i class="fa fa-print"></i> Print Report</button>
      </div>
    </div>
  </div>

  <div class="row">
    <!-- Class List Panel -->
    <div class="col-md-3">
      <div class="card shadow-sm border">
        <div class="card-header bg-primary text-white">
          <h5 class="card-title mb-0">Class List</h5>
        </div>
        <div class="card-body p-0">
          <div class="list-group list-group-flush" id="class-list">
            <!-- List items will be dynamically added here -->
          </div>
        </div>
      </div>
    </div>

    <!-- Report Display Panel -->
    <div class="col-md-9">
      <div class="card shadow-sm border" id="printable">
        <div class="card-body">
          <div>
            <h3 class="text-center text-primary">Evaluation Report</h3>
            <hr class="border-primary">
            <table width="100%">
              <tr>
                <td width="50%"><p><b>Faculty: <span id="facultyName"><?php 
                $faculty = $conn->query("SELECT *,concat(firstname,' ',lastname) as name FROM faculty_list where id = ".$faculty_id)->fetch_assoc();
                echo ucwords($faculty['name']);
                ?></span></b></p></td>
                <td></td>
              </tr>
              <tr>
                <td width="50%"><p><b>Academic Year: <span id="ay"><?php echo $_SESSION['academic']['year'].' '.(ordinal_suffix($_SESSION['academic']['semester'])) ?> Semester</span></b></p></td>
                <td></td>
              </tr>
              <tr>
                <td width="50%"><p><b>Class: <span id="classField"></span></b></p></td>
                <td width="50%"><p><b>Subject: <span id="subjectField"></span></b></p></td>
              </tr>
            </table>
            <p class=""><b>Total Student Evaluated: <span id="tse"></span></b></p>
          </div>
          <fieldset class="border border-info p-2 w-100">
            <legend class="w-auto">Rating Legend</legend>
            <div class="row justify-content-center">
              <div class="col-md-10">
                <div class="table-responsive">
                  <table class="table table-bordered mb-0">
                    <thead class="bg-light">
                      <tr class="text-center">
                        <th width="20%">Rating</th>
                        <th width="80%">Description</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td class="text-center font-weight-bold">5</td>
                        <td>Strongly Agree</td>
                      </tr>
                      <tr>
                        <td class="text-center font-weight-bold">4</td>
                        <td>Agree</td>
                      </tr>
                      <tr>
                        <td class="text-center font-weight-bold">3</td>
                        <td>Uncertain</td>
                      </tr>
                      <tr>
                        <td class="text-center font-weight-bold">2</td>
                        <td>Disagree</td>
                      </tr>
                      <tr>
                        <td class="text-center font-weight-bold">1</td>
                        <td>Strongly Disagree</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </fieldset>
          <?php 
            $q_arr = array();
            $criteria = $conn->query("SELECT * FROM criteria_list where id in (SELECT criteria_id FROM question_list where academic_id = {$_SESSION['academic']['id']} ) order by abs(order_by) asc ");
            while($crow = $criteria->fetch_assoc()):
          ?>
          <table class="table table-condensed wborder mt-3">
            <thead>
              <tr class="bg-primary">
                <th class="p-1"><b><?php echo $crow['criteria'] ?></b></th>
                <th width="5%" class="text-center">1</th>
                <th width="5%" class="text-center">2</th>
                <th width="5%" class="text-center">3</th>
                <th width="5%" class="text-center">4</th>
                <th width="5%" class="text-center">5</th>
              </tr>
            </thead>
            <tbody class="tr-sortable">
              <?php 
              $questions = $conn->query("SELECT * FROM question_list where criteria_id = {$crow['id']} and academic_id = {$_SESSION['academic']['id']} order by abs(order_by) asc ");
              while($row=$questions->fetch_assoc()):
              $q_arr[$row['id']] = $row;
              ?>
              <tr class="bg-white">
                <td class="p-1" width="40%">
                  <?php echo $row['question'] ?>
                </td>
                <?php for($c=1;$c<=5;$c++): ?>
                <td class="text-center">
                  <span class="rate_<?php echo $c.'_'.$row['id'] ?> rates"></span>
                </td>
                <?php endfor; ?>
              </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
          <?php endwhile; ?>
        </div>
      </div>
    </div>
  </div>
</div>

<noscript>
  <style>
    table{
      width:100%;
      border-collapse: collapse;
    }
    table.wborder tr,table.wborder td,table.wborder th{
      border:1px solid gray;
      padding: 3px
    }
    table.wborder thead tr{
      background: #6c757d linear-gradient(180deg,#828a91,#6c757d) repeat-x!important;
        color: #fff;
    }
    .text-center{
      text-align:center;
    } 
    .text-right{
      text-align:right;
    } 
    .text-left{
      text-align:left;
    } 
  </style>
</noscript>
<script>
  $(document).ready(function(){
    load_class()
  })
  function load_class(){
    start_load()
    $.ajax({
      url:"ajax.php?action=get_class",
      method:'POST',
      data:{fid:<?php echo $faculty_id ?>},
      error:function(err){
        console.log(err)
        alert_toast("An error occurred",'error')
        end_load()
      },
      success:function(resp){
        if(resp){
          resp = JSON.parse(resp)
          if(Object.keys(resp).length <= 0 ){
            $('#class-list').html('<a href="javascript:void(0)" class="list-group-item list-group-item-action disabled">No data to be display.</a>')
          }else{
            $('#class-list').html('')
            Object.keys(resp).map(k=>{
            $('#class-list').append('<a href="javascript:void(0)" data-json=\''+JSON.stringify(resp[k])+'\' data-id="'+resp[k].id+'" class="list-group-item list-group-item-action show-result">'+resp[k].class+' - '+resp[k].subj+'</a>')
            })

          }
        }
      },
      complete:function(){
        end_load()
        anchor_func()
        if('<?php echo isset($_GET['rid']) ?>' == 1){
          $('.show-result[data-id="<?php echo isset($_GET['rid']) ? $_GET['rid'] : '' ?>"]').trigger('click')
        }else{
          $('.show-result').first().trigger('click')
        }
      }
    })
  }
  function anchor_func(){
    $('.show-result').click(function(){
      var vars = [], hash;
      var data = $(this).attr('data-json')
        data = JSON.parse(data)
      var _href = location.href.slice(window.location.href.indexOf('?') + 1).split('&');
      for(var i = 0; i < _href.length; i++)
        {
          hash = _href[i].split('=');
          vars[hash[0]] = hash[1];
        }
      window.history.pushState({}, null, './index.php?page=result&rid='+data.id);
      load_report(<?php echo $faculty_id ?>,data.sid,data.id);
      $('#subjectField').text(data.subj)
      $('#classField').text(data.class)
      $('.show-result.active').removeClass('active')
      $(this).addClass('active')
    })
  }
  function load_report($faculty_id, $subject_id,$class_id){
    if($('#preloader2').length <= 0)
    start_load()
    $.ajax({
      url:'ajax.php?action=get_report',
      method:"POST",
      data:{faculty_id: $faculty_id,subject_id:$subject_id,class_id:$class_id},
      error:function(err){
        console.log(err)
        alert_toast("An Error Occurred.","error");
        end_load()
      },
      success:function(resp){
        if(resp){
          resp = JSON.parse(resp)
          if(Object.keys(resp).length <= 0){
            $('.rates').text('')
            $('#tse').text('')
            $('#print-btn').hide()
          }else{
            $('#print-btn').show()
            $('#tse').text(resp.tse)
            $('.rates').text('-')
            var data = resp.data
            Object.keys(data).map(q=>{
              Object.keys(data[q]).map(r=>{
                console.log($('.rate_'+r+'_'+q),data[q][r])
                $('.rate_'+r+'_'+q).text(data[q][r]+'%')
              })
            })
          }
          
        }
      },
      complete:function(){
        end_load()
      }
    })
  }
  $('#print-btn').click(function(){
    start_load()
    var ns =$('noscript').clone()
    var content = $('#printable').html()
    ns.append(content)
    var nw = window.open("Report","_blank","width=900,height=700")
    nw.document.write(ns.html())
    nw.document.close()
    nw.print()
    setTimeout(function(){
      nw.close()
      end_load()
    },750)
  })
</script>