<fieldset>
    <div class="form-group form-float">
        <div class="form-line">
            <input type="text" name="name" class="form-control" required>
            <label class="form-label">First Name*</label>
        </div>
    </div>
    <div class="form-group form-float">
        <div class="form-line">
            <input type="text" name="surname" class="form-control" required>
            <label class="form-label">Last Name*</label>
        </div>
    </div>
    <div class="form-group form-float">
        <div class="form-line">
            <input type="email" name="email" class="form-control" required>
            <label class="form-label">Email*</label>
        </div>
    </div>
    <div class="form-group form-float">
        <div class="form-line">
            <textarea name="address" cols="30" rows="3" class="form-control no-resize" required></textarea>
            <label class="form-label">Address*</label>
        </div>
    </div>
    <div class="form-group form-float">
        <div class="form-line">
            <input min="18" type="number" name="age" class="form-control" required>
            <label class="form-label">Age*</label>
        </div>
        <div class="help-info">The warning step will show up if age is less than 18</div>
    </div>
</fieldset>