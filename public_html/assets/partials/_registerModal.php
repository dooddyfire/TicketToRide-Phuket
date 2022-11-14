<script>
function onChange() {
  const password = document.getElementById("password1");
  const confirm = document.getElementById("confirm1");
  if (confirm.value === password.value) {
    confirm.setCustomValidity('');
  } else {
    confirm.setCustomValidity('ยืนยันรหัสผ่านไม่ถูกต้อง');
  }
}
</script>
<!-- Modal -->
<div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="registerModalLabel">สมัครสมาชิก</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="assets/partials/_handleRegister.php" method="POST">
          <div class="mb-3">
              <label for="cfirstname" class="form-label">ชื่อ</label>
              <input type="text" class="form-control" id="cfirstname" name="cfirstname">
          </div>
          <div class="mb-3">
              <label for="clastname" class="form-label">นามสกุล</label>
              <input type="text" class="form-control" id="clastname" name="clastname">
          </div>

          <div class="mb-3">
              <label for="c" class="form-label">เบอร์โทรศัพท์</label>
              <input type="tel" class="form-control" id="cphone" name="cphone" pattern="[0-9]{10}" placeholder="12345678" required> 
          </div>

          <div class="mb-3">
              <label for="c" class="form-label">ชื่อผู้ใช้</label>
              <input type="text" class="form-control" id="username" name="username">
          </div>

          <div class="mb-3">
              <label for="c" class="form-label">รหัสผ่าน</label>
              <input name="password" type="password" class="form-control" id="password1" onChange="onChange()">
          </div>

          <div class="mb-3">
              <label for="c" class="form-label">ยืนยันรหัสผ่าน</label>
              <input name="confirm" type="password" class="form-control" id="confirm1" onChange="onChange()">
          </div>

          <button type="submit" class="btn btn-danger" name="submit">สมัครสมาชิก</button>
        </form>
      </div>
      <div class="modal-footer">
        <!-- Add anything here in the future -->
      </div>
    </div>
  </div>
</div>
