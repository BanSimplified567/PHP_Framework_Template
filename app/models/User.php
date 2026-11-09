<?php
// app/Models/User.php
class User
{
  protected $pdo;

  public function __construct($pdo)
  {
    $this->pdo = $pdo;
  }

  public function findByEmail($email)
  {
    $stmt = $this->pdo->prepare("
      SELECT * FROM users
      WHERE email = ? AND is_active = 1
    ");
    $stmt->execute([$email]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  public function findById($id)
  {
    $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  public function findByAllSecurityAnswers($answers, $questionIds)
  {
    $sql = "
      SELECT u.id, u.email, u.first_name, u.last_name
      FROM users u
      WHERE u.is_active = 1
      AND u.secret_question_setup = 1
    ";
    $params = [];

    for ($i = 1; $i <= 5; $i++) {
      $sql .= "
        AND EXISTS (
          SELECT 1 FROM user_secret_questions usq{$i}
          WHERE usq{$i}.user_id = u.id
          AND usq{$i}.question_id = :question_id_{$i}
          AND LOWER(usq{$i}.answer) = LOWER(:answer_{$i})
        )
      ";
      $params[':question_id_' . $i] = $questionIds[$i] ?? 0;
      $params[':answer_' . $i] = $answers[$i] ?? '';
    }

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  public function emailExists($email, $excludeId = null)
  {
    $sql = "SELECT id FROM users WHERE email = ?";
    $params = [$email];
    if ($excludeId !== null) {
      $sql .= " AND id != ?";
      $params[] = $excludeId;
    }
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->rowCount() > 0;
  }

  public function create($data, $secretQuestions, $secretAnswers)
  {
    $this->pdo->beginTransaction();

    try {
      $stmt = $this->pdo->prepare("
        INSERT INTO users
        (username, email, password, first_name, last_name, phone, role, is_active, created_at, secret_question_setup)
        VALUES (?, ?, ?, ?, ?, ?, ?, 1, NOW(), 1)
      ");
      $stmt->execute([
        $data['username'],
        $data['email'],
        $data['password'],
        $data['first_name'],
        $data['last_name'],
        $data['phone'],
        $data['role']
      ]);

      $userId = $this->pdo->lastInsertId();

      for ($i = 1; $i <= 5; $i++) {
        $stmt = $this->pdo->prepare("
          INSERT INTO user_secret_questions
          (user_id, question_id, answer, created_at)
          VALUES (?, ?, ?, NOW())
        ");
        $stmt->execute([
          $userId,
          $secretQuestions[$i] ?? '',
          $secretAnswers[$i] ?? ''
        ]);
      }

      $this->pdo->commit();
      return $userId;
    } catch (Exception $e) {
      $this->pdo->rollBack();
      throw $e;
    }
  }

  public function updatePassword($userId, $hashedPassword)
  {
    $stmt = $this->pdo->prepare("
      UPDATE users
      SET password = ?, reset_token = NULL, reset_expires = NULL
      WHERE id = ?
    ");
    return $stmt->execute([$hashedPassword, $userId]);
  }

  public function updateEmail($userId, $newEmail)
  {
    $stmt = $this->pdo->prepare("
      UPDATE users
      SET email = ?, username = ?
      WHERE id = ?
    ");
    return $stmt->execute([$newEmail, $newEmail, $userId]);
  }

  public function updateLastLogin($userId)
  {
    $stmt = $this->pdo->prepare("
      UPDATE users
      SET last_login = NOW()
      WHERE id = ?
    ");
    return $stmt->execute([$userId]);
  }

  public function updateLastLogout($userId)
  {
    $stmt = $this->pdo->prepare("
      UPDATE users
      SET last_logout = NOW()
      WHERE id = ?
    ");
    return $stmt->execute([$userId]);
  }

  public function handleFailedLogin($userId, $currentAttempts)
  {
    $newAttempts = $currentAttempts + 1;
    $lockedUntil = null;

    if ($newAttempts >= 5) {
      $stmt = $this->pdo->prepare("SELECT DATE_ADD(NOW(), INTERVAL 5 MINUTE) as locked_until");
      $stmt->execute();
      $lockedUntil = $stmt->fetch(PDO::FETCH_ASSOC)['locked_until'];
    }

    $stmt = $this->pdo->prepare("
      UPDATE users
      SET failed_attempts = ?, locked_until = ?
      WHERE id = ?
    ");
    $stmt->execute([$newAttempts, $lockedUntil, $userId]);

    return [
      'attempts' => $newAttempts,
      'locked' => $newAttempts >= 5,
      'locked_until' => $lockedUntil
    ];
  }

  public function resetFailedAttempts($userId)
  {
    $stmt = $this->pdo->prepare("
      UPDATE users
      SET failed_attempts = 0, locked_until = NULL
      WHERE id = ?
    ");
    return $stmt->execute([$userId]);
  }

  public function getActiveAdmins()
  {
    $stmt = $this->pdo->prepare("SELECT email FROM users WHERE role = 'admin' AND is_active = 1");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function getSecurityQuestions($limit = 5)
  {
    $stmt = $this->pdo->prepare("
      SELECT id, question
      FROM secret_questions
      WHERE is_active = 1
      ORDER BY id
      LIMIT ?
    ");
    $stmt->execute([$limit]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
}
