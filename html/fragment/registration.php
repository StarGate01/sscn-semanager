<h4>Segel-Surf-Club Neufahrn e.V. - Surfers' Weekend</h4>
<h2>Anmeldung Schnuppersurfen</h2>

<form class="needs-validation" novalidate>
    <div class="form-group row">
        <div class="col-md-6 mb-2">
            <label for="firstname">Vorname</label>
            <input type="text" class="form-control" id="firstname" placeholder="Vorname" required>
            <div class="invalid-feedback">
                Bitte ergänzen Sie Ihre Angaben
            </div>
        </div>
        <div class="col-md-6 mb-2">
            <label for="lastname">Nachname</label>
            <input type="text" class="form-control" id="lastname" placeholder="Nachname" required>
            <div class="invalid-feedback">
                Bitte ergänzen Sie Ihre Angaben
            </div>
        </div>
    </div>

    <div class="form-group row">
        <div class="col-md-8 mb-2">
            <label for="firstname">Straße</label>
            <input type="text" class="form-control" id="address" placeholder="Straße" required>
            <div class="invalid-feedback">
                Bitte ergänzen Sie Ihre Angaben
            </div>
        </div>
        <div class="col-md-4 mb-2">
            <label for="lastname">PLZ / Ort</label>
            <input type="text" class="form-control" id="city" placeholder="PLZ / Ort" required>
            <div class="invalid-feedback">
                Bitte ergänzen Sie Ihre Angaben
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="col-md-2 mb-2">
            <label for="firstname">Geburtsdatum</label>
            <input type="date" class="form-control" id="birth" placeholder="Geburtsdatum" required>
            <div class="invalid-feedback">
                Bitte ergänzen Sie Ihre Angaben
            </div>
        </div>
    </div>

    <div class="form-group row">
        <div class="col-md-6 mb-2">
            <label for="firstname">E-Mail</label>
            <input type="email" class="form-control" id="email" placeholder="E-Mail" required>
            <div class="invalid-feedback">
                Bitte ergänzen Sie Ihre Angaben
            </div>
        </div>
        <div class="col-md-6 mb-2">
            <label for="lastname">Telefon</label>
            <input type="tel" class="form-control" id="phone" placeholder="Telefon" required>
            <div class="invalid-feedback">
                Bitte ergänzen Sie Ihre Angaben
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="mb-2 form-check">
            <input class="form-check-input" type="checkbox" id="newsletter">
            <label class="form-check-label" for="agb">
                Ich möchte per E-Mail über zukünftige Vereinsaktionen informiert werden, und an der Verlosung des kostenlosen Surfkurses inklusive Surfschein und Unterrichtsmaterialien teilnehmen.
            </label>
        </div>
    </div>

    <div class="form-group">
        <div class="mb-2 form-check">
            <input class="form-check-input" type="checkbox" id="agb" required>
            <label class="form-check-label" for="agb">
                Ich akzeptiere die Bestimmungen zum Datenschutz.
            </label>
            <div class="invalid-feedback">
                Sie müssen die Bestimmungen zum Datenschutz akzeptieren um sich anzumelden.
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="mb-2">
            Der Teilnehmer versichert, dass er gut schwimmen kann. Der Veranstalter übernimmt keinerlei Haftung für selbstverschuldete Unfälle zu Wasser oder zu Land.
        </div>
    </div>

    <div class="form-group">
        <div class="mb-2">
            <label for="signature">Unterschrift Teilnehmer, bei Minderjährigen Unterschrift der/des Erziehungsberechtigten 🖊️</label>
            <div class="canvas_wrapper">
                <canvas class="form-control"></canvas>
                <button type="button" class="btn btn-danger btn-close canvas_clear"></button>
                <input class="form-check-input signature" type="text" id="signature">
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
            Neufahrn, der <?php echo date("d.m.Y"); ?>
        </div>
    </div>

    <div class="form-group">
        <button class="btn btn-primary" type="submit">Speichern und Anmelden</button>
    </div>
</form>