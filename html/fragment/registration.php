<h4>Surfers' Weekend</h4>
<h2>Anmeldung Schnuppersurfen</h2>

<form class="needs-validation" action="index.php" method="post" novalidate>
    <div class="form-group row">
        <div class="col-md-6 mb-2">
            <label for="firstname">Vorname</label>
            <input type="text" class="form-control" name="firstname" id="firstname" placeholder="Vorname" value="<?php echo $data->firstname; ?>" required>
            <div class="invalid-feedback">
                Bitte ergänzen Sie Ihren Vornamen
            </div>
        </div>
        <div class="col-md-6 mb-2">
            <label for="lastname">Nachname</label>
            <input type="text" class="form-control" name="lastname" id="lastname" placeholder="Nachname" value="<?php echo $data->lastname; ?>" required>
            <div class="invalid-feedback">
                Bitte ergänzen Sie Ihren Nachnamen
            </div>
        </div>
    </div>

    <div class="form-group row">
        <div class="col-md-8 mb-2">
            <label for="firstname">Adresse</label>
            <input type="text" class="form-control" name="address" id="address" placeholder="Adresse" value="<?php echo $data->address; ?>" required>
            <div class="invalid-feedback">
                Bitte ergänzen Sie Ihre Adresse
            </div>
        </div>
        <div class="col-md-4 mb-2">
            <label for="lastname">PLZ / Ort</label>
            <input type="text" class="form-control" name="city" id="city" placeholder="PLZ / Ort" value="<?php echo $data->city; ?>" required>
            <div class="invalid-feedback">
                Bitte ergänzen Sie Ihre/n PLZ / Ort
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="col-md-3 mb-2">
            <label for="firstname">Geburtsdatum</label>
            <input type="date" class="form-control" name="birth" id="birth" placeholder="Geburtsdatum" value="<?php echo $data->birth; ?>" required>
            <div class="invalid-feedback">
                Bitte ergänzen Sie Ihr Geburtsdatum
            </div>
        </div>
    </div>

    <div class="form-group row">
        <div class="col-md-6 mb-2">
            <label for="firstname">E-Mail Adresse</label>
            <input type="email" class="form-control" name="email" id="email" placeholder="E-Mail Adresse" value="<?php echo $data->email; ?>" required>
            <div class="invalid-feedback">
                Bitte ergänzen Sie Ihre E-Mail Adresse
            </div>
        </div>
        <div class="col-md-6 mb-2">
            <label for="lastname">Telefonnummer</label>
            <input type="tel" class="form-control" name="phone" id="phone" placeholder="Telefonnummer" value="<?php echo $data->phone; ?>" required>
            <div class="invalid-feedback">
                Bitte ergänzen Sie Ihre Telefonnummer
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="mb-2 form-check">
            <input class="form-check-input" type="checkbox" name="newsletter" id="newsletter" <?php echo ($data->newsletter)? "checked":""; ?>>
            <label class="form-check-label" for="agb">
                Ich möchte per E-Mail über zukünftige Vereinsaktionen informiert werden, und an der Verlosung eines kostenlosen Surfkurses und eines SUP-Kurses teilnehmen.
            </label>
        </div>
    </div>

    <div class="form-group">
        <div class="mb-2 form-check">
            <input class="form-check-input" type="checkbox" name="agb" id="agb" required <?php echo ($data->agb)? "checked":""; ?>>
            <label class="form-check-label" for="agb">
                Ich akzeptiere die <a href="privacy.php" target="_blank">Bestimmungen zum Datenschutz</a>.
            </label>
            <div class="invalid-feedback">
                Sie müssen die <a href="privacy.php" target="_blank">Bestimmungen zum Datenschutz</a> akzeptieren um sich anzumelden.
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="mb-2">
            Der/die Teilnehmer/in versichert, dass er/sie gut schwimmen kann. Der Veranstalter übernimmt keinerlei Haftung für selbstverschuldete Unfälle zu Wasser oder zu Land.
        </div>
    </div>

    <div class="form-group">
        <div class="mb-2">
            <label for="signature">Unterschrift Teilnehmer/in, bei Minderjährigen Unterschrift der/des Erziehungsberechtigten 🖊️</label>
            <div class="canvas_wrapper">
                <canvas class="form-control"></canvas>
                <button type="button" class="btn btn-danger btn-close canvas_clear"></button>
                <input class="form-check-input signature" type="text" name="signature" id="signature" value="<?php echo $data->signature; ?>">
            </div>
        </div>
        <div class="invalid-feedback" id="signature_error" style="display: none;">
            <div class="mb-2">
                Bitte ergänzen Sie Ihre Unterschrift
            </div>
        </div>
    </div>

    <div class="form-group row">
        <div class="col-md-6 mb-2">
            Neufahrn, den <?php echo date("d.m.Y"); ?>
        </div>
    </div>

    <div class="form-group">
        <button class="btn btn-primary" type="submit">Registrieren und Speichern</button>
    </div>
</form>